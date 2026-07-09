# Agenda Prenotazioni Ristorante — Guida completa

Applicazione web per gestire le prenotazioni del ristorante.  
I dati sono salvati in **due file JSON sul server**:

- `data/prenotazioni.json`
- `data/impostazioni.json`

Nessun token, nessun account speciale: carichi i file sul tuo hosting e funziona.

---

## Come funziona su qualsiasi hosting

```
tuo-dominio.it/
├── index.html          ← l'agenda
├── api/
│   └── save.php        ← salva i dati (serve PHP)
└── data/
    ├── prenotazioni.json
    └── impostazioni.json
```

1. **Lettura** — il browser scarica i file JSON da `data/`
2. **Scrittura** — quando aggiungi/modifichi una prenotazione, chiama `api/save.php` che aggiorna il file

### Hosting compatibili

| Hosting | Funziona? |
|---------|-----------|
| **Aruba, OVH, SiteGround, ecc.** (con PHP) | ✅ Sì — carica tutta la cartella via FTP |
| **GitHub Pages** | ❌ No salvataggio (solo file statici, niente PHP) |
| **Netlify / Vercel statico** | ❌ No salvataggio senza funzioni server |
| **PC locale (test)** | ✅ `npm start` → http://localhost:3080 |

> **In sintesi:** ti serve un hosting con **PHP** (quasi tutti quelli a pagamento). GitHub Pages da solo non basta per salvare.

---

## Installazione su hosting (Aruba, OVH, ecc.)

1. Scarica il progetto da GitHub
2. Carica **tutta la cartella** sul server via FTP:
   - `index.html`
   - cartella `api/` (con `save.php`)
   - cartella `data/` (con i due file JSON)
3. Apri `https://tuo-dominio.it/index.html`
4. In **Impostazioni** verifica che compaia **"Salvataggio attivo"**

### Permessi cartella `data/`

La cartella `data/` deve essere **scrivibile** da PHP (di solito chmod `755` o `775`).

---

## Test in locale (sul PC)

```bash
npm start
```

Apri **http://localhost:3080**

---

## Panoramica funzionalità

| Area | Cosa puoi fare |
|------|----------------|
| **Prenotazioni** | Aggiungere, filtrare, cambiare stato, eliminare |
| **Contatti rapidi** | Chiamata telefonica e WhatsApp ITA/ENG |
| **Capienza** | Limite posti generale + override per giorno |
| **Calendario** | Vista mensile capienza (accordion, chiuso di default) |
| **Orari** | Menu a tendina pranzo/cena ogni 15 min |
| **Export** | CSV e PDF |
| **Report** | Statistiche per periodo |

---

## Calendario capienza

Pannello **accordion** (chiuso di default) tra prenotazioni e lista:

- Clic su **Calendario Capienza** per aprire/chiudere
- Ogni giorno: posti occupati/massimo, colori verde/arancio/rosso
- Click su un giorno → filtra le prenotazioni

---

## Impostazioni

### Capienza, orari, messaggi WhatsApp

Come prima: posti massimi, capienza per giorno, orari pranzo/cena, messaggi con placeholder `{name}`, `{people}`, `{date}`, `{time}`, `{phone}`, `{notes}`.

### Stato salvataggio

In cima alle Impostazioni vedi se il salvataggio sui file JSON è attivo.  
**Ricarica dati** — rilegge i file dal server.

---

## Backup

Prima di ogni salvataggio viene creato automaticamente:

- `data/prenotazioni.backup.json`
- `data/impostazioni.backup.json`

Puoi anche scaricare manualmente i file JSON via FTP.

---

## Spostare su altro hosting

1. Scarica via FTP: `index.html`, `api/`, `data/`
2. Caricali sul nuovo hosting
3. Fatto — stessi dati, stessa agenda

Nessuna riconfigurazione, nessun database da esportare.

---

## Risoluzione problemi

| Problema | Soluzione |
|----------|-----------|
| Banner giallo "Salvataggio non disponibile" | L'hosting non ha PHP o `api/save.php` non è caricato |
| Errore scrittura | Controlla permessi cartella `data/` (scrivibile) |
| Prenotazioni non si vedono | Clicca **Ricarica dati** in Impostazioni |
| GitHub Pages | Non supporta salvataggio — usa hosting PHP |

---

**Repository:** https://github.com/salvatoremasuri-debug/codula
