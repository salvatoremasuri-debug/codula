import http from "http";
import fs from "fs/promises";
import path from "path";
import { fileURLToPath } from "url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = __dirname;
const DATA_DIR = path.join(ROOT, "data");
const BOOKINGS_FILE = path.join(DATA_DIR, "prenotazioni.json");
const SETTINGS_FILE = path.join(DATA_DIR, "impostazioni.json");
const PORT = Number(process.env.PORT) || 3080;

const MIME = {
  ".html": "text/html; charset=utf-8",
  ".js": "text/javascript; charset=utf-8",
  ".mjs": "text/javascript; charset=utf-8",
  ".css": "text/css; charset=utf-8",
  ".json": "application/json; charset=utf-8",
  ".md": "text/markdown; charset=utf-8"
};

async function readJson(file, fallback) {
  try {
    const raw = await fs.readFile(file, "utf8");
    return JSON.parse(raw);
  } catch {
    return fallback;
  }
}

async function writeJson(file, data) {
  await fs.mkdir(path.dirname(file), { recursive: true });
  const backup = file.replace(/\.json$/, ".backup.json");
  try {
    await fs.copyFile(file, backup);
  } catch {
    // Nessun backup se il file non esiste ancora.
  }
  await fs.writeFile(file, JSON.stringify(data, null, 2) + "\n", "utf8");
}

function sendJson(res, status, payload) {
  res.writeHead(status, {
    "Content-Type": "application/json; charset=utf-8",
    "Cache-Control": "no-store"
  });
  res.end(JSON.stringify(payload));
}

async function readBody(req) {
  const chunks = [];
  for await (const chunk of req) chunks.push(chunk);
  const raw = Buffer.concat(chunks).toString("utf8");
  return raw ? JSON.parse(raw) : null;
}

async function handleApi(req, res, url) {
  if (url.pathname === "/api/prenotazioni" && req.method === "GET") {
    const data = await readJson(BOOKINGS_FILE, []);
    return sendJson(res, 200, data);
  }

  if (url.pathname === "/api/prenotazioni" && req.method === "PUT") {
    const data = await readBody(req);
    if (!Array.isArray(data)) {
      return sendJson(res, 400, { error: "Formato non valido: atteso un array." });
    }
    await writeJson(BOOKINGS_FILE, data);
    return sendJson(res, 200, { ok: true });
  }

  if (url.pathname === "/api/impostazioni" && req.method === "GET") {
    const data = await readJson(SETTINGS_FILE, {});
    return sendJson(res, 200, data);
  }

  if (url.pathname === "/api/impostazioni" && req.method === "PUT") {
    const data = await readBody(req);
    if (!data || typeof data !== "object" || Array.isArray(data)) {
      return sendJson(res, 400, { error: "Formato non valido: atteso un oggetto." });
    }
    await writeJson(SETTINGS_FILE, data);
    return sendJson(res, 200, { ok: true });
  }

  if (url.pathname === "/api/health" && req.method === "GET") {
    return sendJson(res, 200, { ok: true });
  }

  sendJson(res, 404, { error: "Endpoint non trovato." });
}

async function serveStatic(req, res, url) {
  let filePath = url.pathname === "/" ? "/index.html" : url.pathname;
  filePath = path.normalize(filePath).replace(/^(\.\.[/\\])+/, "");
  const absPath = path.join(ROOT, filePath);

  if (!absPath.startsWith(ROOT)) {
    res.writeHead(403);
    res.end("Forbidden");
    return;
  }

  try {
    const stat = await fs.stat(absPath);
    if (!stat.isFile()) throw new Error("Not a file");
    const ext = path.extname(absPath).toLowerCase();
    const content = await fs.readFile(absPath);
    res.writeHead(200, {
      "Content-Type": MIME[ext] || "application/octet-stream",
      "Cache-Control": ext === ".html" ? "no-store" : "public, max-age=300"
    });
    res.end(content);
  } catch {
    res.writeHead(404);
    res.end("Not found");
  }
}

const server = http.createServer(async (req, res) => {
  const url = new URL(req.url || "/", `http://${req.headers.host || "localhost"}`);

  if (url.pathname.startsWith("/api/")) {
    try {
      await handleApi(req, res, url);
    } catch (error) {
      sendJson(res, 500, { error: error.message || "Errore server." });
    }
    return;
  }

  await serveStatic(req, res, url);
});

server.listen(PORT, () => {
  console.log("Agenda Prenotazioni in esecuzione:");
  console.log("  App:  http://localhost:" + PORT);
  console.log("  Dati: " + path.join(DATA_DIR, "prenotazioni.json"));
});
