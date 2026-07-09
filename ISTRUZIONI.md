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
├── index.html
├── api/
│   ├── login.php
│   ├── logout.php
│   ├── check-auth.php
│   ├── load.php
│   └── save.php
├── config/
│   └── auth.php        ← username e password (non accessibile dal web)
├── includes/
└── data/
    ├── prenotazioni.json
    └── impostazioni.json
```

1. **Login** — schermata di accesso con username/password
2. **Lettura** — solo dopo il login, tramite `api/load.php`
3. **Scrittura** — solo dopo il login, tramite `api/save.php`
4. I file in `data/` **non sono accessibili** direttamente dal browser

### Hosting compatibili

| Hosting | Funziona? |
|---------|-----------|
| **Aruba, OVH, SiteGround, ecc.** (con PHP) | ✅ Sì — carica tutta la cartella via FTP |
| **GitHub Pages** | ❌ No salvataggio (solo file statici, niente PHP) |
| **Netlify / Vercel statico** | ❌ No salvataggio senza funzioni server |
| **PC locale (test)** | ✅ `npm start` → http://localhost:3080 |

> **In sintesi:** ti serve un hosting con **PHP** (quasi tutti quelli a pagamento). GitHub Pages da solo non basta per salvare.

---

## Installazione su SiteGround (o hosting PHP)

1. Scarica il progetto da GitHub
2. Carica **tutta la cartella** sul server via FTP / File Manager
3. Crea le credenziali di accesso:
   - Copia `config/auth.sample.php` in `config/auth.php`
   - Modifica username e password in `config/auth.php`
4. Apri `https://tuo-dominio.it/`
5. Accedi con le credenziali impostate

### Credenziali predefinite (se non modifichi auth.php)

| Campo | Valore |
|-------|--------|
| Username | `admin` |
| Password | `agenda2026` |

> **Cambia subito la password** in `config/auth.php` dopo il primo accesso.

### Permessi cartelle

| Cartella | Permesso |
|----------|----------|
| `data/` | Scrivibile da PHP (chmod `755` o `775`) |
| `config/` | Non accessibile dal web (c'è già `.htaccess`) |

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
| **Accesso protetto** | Login con username/password |

---

## Sicurezza e accesso

- All'apertura compare la **schermata di login**
- Solo utenti autorizzati possono vedere e modificare le prenotazioni
- I file JSON in `data/` sono **bloccati** dall'accesso diretto
- Pulsante **Esci** in alto a destra per chiudere la sessione
- Le credenziali si configurano in `config/auth.php` sul server (mai nel browser)

Per cambiare password, modifica `config/auth.php`:

```php
return [
    "username" => "tuo_nome",
    "password" => "tua_password_sicura"
];
```

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
