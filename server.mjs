import http from "http";
import fs from "fs/promises";
import path from "path";
import { fileURLToPath } from "url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = __dirname;
const DATA_DIR = path.join(ROOT, "data");
const PORT = Number(process.env.PORT) || 3080;

const MIME = {
  ".html": "text/html; charset=utf-8",
  ".js": "text/javascript; charset=utf-8",
  ".css": "text/css; charset=utf-8",
  ".json": "application/json; charset=utf-8",
  ".php": "text/plain; charset=utf-8"
};

async function readBody(req) {
  const chunks = [];
  for await (const chunk of req) chunks.push(chunk);
  const raw = Buffer.concat(chunks).toString("utf8");
  return raw ? JSON.parse(raw) : {};
}

async function writeDataFile(file, data) {
  const allowed = ["prenotazioni", "impostazioni"];
  if (!allowed.includes(file)) {
    const error = new Error("File non valido.");
    error.status = 400;
    throw error;
  }
  if (file === "prenotazioni" && !Array.isArray(data)) {
    const error = new Error("Formato prenotazioni non valido.");
    error.status = 400;
    throw error;
  }
  if (file === "impostazioni" && (typeof data !== "object" || Array.isArray(data))) {
    const error = new Error("Formato impostazioni non valido.");
    error.status = 400;
    throw error;
  }

  await fs.mkdir(DATA_DIR, { recursive: true });
  const target = path.join(DATA_DIR, file + ".json");
  const backup = target.replace(/\.json$/, ".backup.json");
  try {
    await fs.copyFile(target, backup);
  } catch {
    // Nessun backup se il file non esiste.
  }
  await fs.writeFile(target, JSON.stringify(data, null, 2) + "\n", "utf8");
}

function sendJson(res, status, payload) {
  res.writeHead(status, {
    "Content-Type": "application/json; charset=utf-8",
    "Cache-Control": "no-store"
  });
  res.end(JSON.stringify(payload));
}

const server = http.createServer(async (req, res) => {
  const url = new URL(req.url || "/", "http://localhost");

  if ((url.pathname === "/api/save" || url.pathname === "/api/save.php") && req.method === "POST") {
    try {
      const body = await readBody(req);
      await writeDataFile(body.file, body.data);
      sendJson(res, 200, { ok: true, file: body.file });
    } catch (error) {
      sendJson(res, error.status || 500, { error: error.message || "Errore server." });
    }
    return;
  }

  let filePath = url.pathname === "/" ? "/index.html" : url.pathname;
  const absPath = path.join(ROOT, filePath);
  if (!absPath.startsWith(ROOT)) {
    res.writeHead(403);
    res.end("Forbidden");
    return;
  }

  try {
    const stat = await fs.stat(absPath);
    if (!stat.isFile()) throw new Error("Not found");
    const ext = path.extname(absPath).toLowerCase();
    const content = await fs.readFile(absPath);
    res.writeHead(200, {
      "Content-Type": MIME[ext] || "application/octet-stream",
      "Cache-Control": ext === ".html" || ext === ".json" ? "no-store" : "public, max-age=300"
    });
    res.end(content);
  } catch {
    res.writeHead(404);
    res.end("Not found");
  }
});

server.listen(PORT, () => {
  console.log("Agenda locale: http://localhost:" + PORT);
  console.log("Dati in: " + DATA_DIR);
});
