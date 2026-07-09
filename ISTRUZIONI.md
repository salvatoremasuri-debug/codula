# Agenda Prenotazioni Ristorante — Guida completa

Applicazione web per gestire le prenotazioni del ristorante.  
I dati sono salvati come **file JSON nel repository GitHub** (`data/prenotazioni.json` e `data/impostazioni.json`), tramite API GitHub. Funziona su **GitHub Pages** e su qualsiasi hosting statico.

---

## Come aprire l'agenda

### Link (GitHub Pages, se attivato)

https://salvatoremasuri-debug.github.io/codula/

### Anteprima immediata

https://htmlpreview.github.io/?https://raw.githubusercontent.com/salvatoremasuri-debug/codula/main/index.html

**Repository:** https://github.com/salvatoremasuri-debug/codula

---

## Configurazione database GitHub (obbligatoria, una tantum)

Alla prima apertura vai in **Impostazioni → Database GitHub** e compila:

| Campo | Esempio |
|-------|---------|
| Utente / Organizzazione | `salvatoremasuri-debug` |
| Repository | `codula` |
| Branch | `main` |
| Token GitHub | il tuo token personale |

Poi clicca **Salva e connetti**.

### Come creare il token GitHub

1. Vai su https://github.com/settings/tokens?type=beta (Fine-grained tokens)
2. **Generate new token**
3. Nome: `agenda-prenotazioni`
4. Repository access: **Only select repositories** → scegli `codula`
5. Permissions → **Contents**: Read and write
6. Genera e copia il token (`github_pat_...`)
7. Incollalo nel campo Token dell'agenda

> Il token resta salvato **solo nel browser** del dispositivo che usi (non nel repository).

### File dati nel repository

| File | Contenuto |
|------|-----------|
| `data/prenotazioni.json` | Tutte le prenotazioni |
| `data/impostazioni.json` | Capienza, orari, messaggi WhatsApp |

Ogni modifica nell'agenda aggiorna questi file su GitHub. Puoi vederli e ripristinarli dalla cronologia del repository.

### Pulsanti utili

- **Test connessione** — verifica che token e repository siano corretti
- **Ricarica da GitHub** — scarica di nuovo i dati dal repository

---

## Panoramica funzionalità

| Area | Cosa puoi fare |
|------|----------------|
| **Prenotazioni** | Aggiungere, filtrare, cambiare stato, eliminare |
| **Contatti rapidi** | Chiamata telefonica e WhatsApp ITA/ENG con messaggio personalizzato |
| **Capienza** | Limite posti generale + override per singolo giorno |
| **Impostazioni** | Messaggi WhatsApp, tema chiaro/scuro |
| **Export** | CSV e PDF per un giorno specifico |
| **Report** | Statistiche per periodo con filtro stato |
| **Sicurezza dati** | Database JSON su GitHub con cronologia commit |

---

## Calendario capienza

Tra il modulo prenotazioni e la lista trovi il **Calendario Capienza** (in un pannello **accordion**, chiuso di default):

- Clicca su **Calendario Capienza** per aprirlo o chiuderlo

- Ogni giorno mostra **posti occupati / posti massimi** (es. `12/50`)
- Sotto compare il numero di **prenotazioni attive** del giorno
- Colori:
  - **Verde** — disponibilità ok
  - **Arancio** — quasi pieno (≥ 80%)
  - **Rosso** — capienza superata
- **★** — giorno con capienza personalizzata nelle Impostazioni
- **Clic su un giorno** — filtra subito le prenotazioni di quella data
- Frecce **‹ ›** — cambia mese

---

### Barra superiore

- **Tema scuro / Tema chiaro** — alterna l'aspetto visivo
- **Impostazioni** — apre il pannello di configurazione avanzata

### Nuova prenotazione

Compila i campi e premi **Aggiungi**:

| Campo | Obbligatorio | Note |
|-------|:------------:|------|
| Nome cliente | Sì | |
| Numero persone | Sì | Minimo 1 |
| Telefono | Sì | Solo cifre, es. `393331234567` (prefisso internazionale senza `+`) |
| Data | Sì | |
| Orario | Sì | Seleziona dal menu (orari configurati in Impostazioni) |
| Note | No | Es. allergie, seggiolone, tavolo preferito |

**Controllo capienza:** se per quel giorno non ci sono abbastanza posti liberi, la prenotazione viene bloccata con un messaggio di avviso.

**Svuota tutto** — elimina **tutte** le prenotazioni (chiede conferma).

### Lista prenotazioni

Ogni prenotazione mostra:
- Nome e numero persone
- Orario, telefono, stato, eventuali note

#### Filtri

| Pulsante | Effetto |
|----------|---------|
| **Oggi** | Solo prenotazioni di oggi (filtro predefinito) |
| **Domani** | Solo prenotazioni di domani |
| **Tutte** | Nessun filtro per data |
| **Filtra data** | Usa il selettore data accanto ai pulsanti |
| **Reset** | Torna alla vista "Tutte" |

#### Intestazione per giorno

Per ogni data compare: `GG/MM/AAAA - Posti: occupati/massimo`

- Il conteggio **esclude** prenotazioni annullate e no-show
- Se superi la capienza, il contatore diventa **rosso**

#### Azioni su ogni prenotazione

| Icona | Azione |
|-------|--------|
| 📞 | Apre la chiamata telefonica |
| 🇮🇹 | WhatsApp con messaggio in italiano |
| 🇬🇧 | WhatsApp con messaggio in inglese |
| **?** | Stato: Da confermare |
| **OK** | Stato: Confermata |
| **NO** | Stato: Annullata |
| 👁️❌ | Stato: No-show |
| 🗑️ | Elimina la prenotazione |

---

## Impostazioni

Apri **Impostazioni** dalla barra in alto.

### Database GitHub

Vedi la sezione **Configurazione database GitHub** all'inizio di questo documento.

### Capienza posti

#### Numero posti massimi (generale)

Valore di default per tutti i giorni in cui non hai impostato una capienza specifica.  
Esempio: `50` posti.

#### Capienza specifica per giorno

Per giorni speciali (sabato sera, feste, eventi):

1. Scegli la **data**
2. Inserisci i **posti** per quel giorno
3. Clicca **Salva capienza giorno**

Sotto compare la lista delle capienze impostate. Puoi **Rimuovi** per tornare al valore generale.

> La capienza specifica ha priorità sul valore generale solo per la data scelta.

### Orari prenotabili

Nel menu **Nuova Prenotazione**, il campo orario è un menu a tendina con solo gli orari che configuri qui.

**Default:**
- **Pranzo:** 12:00 – 14:30, ogni 15 minuti
- **Cena:** 19:00 – 22:30, ogni 15 minuti

**Come configurare:**
1. Attiva/disattiva **Servizio pranzo** e/o **Servizio cena**
2. Imposta **inizio** e **fine** per ogni servizio
3. Scegli l'**intervallo** (15, 30 o 60 minuti)
4. Controlla l'anteprima degli orari generati
5. Premi **Salva impostazioni**

Esempi:
- Solo cena: disattiva il pranzo
- Pranzo 12:30–14:00 ogni 30 min: imposta inizio/fine e intervallo 30

### Messaggi WhatsApp

Due campi di testo:
- **Messaggio WhatsApp ITA**
- **Messaggio WhatsApp ENG**

#### Placeholder cliccabili

Sotto i messaggi trovi una barra con pulsanti da inserire con un clic:

| Placeholder | Viene sostituito con |
|-------------|----------------------|
| `{name}` | Nome cliente |
| `{people}` | Numero persone |
| `{date}` | Data (formato GG/MM/AAAA) |
| `{time}` | Orario |
| `{phone}` | Telefono |
| `{notes}` | Note (vuoto se assente) |

**Come usarli:**
1. Clicca nel campo messaggio ITA o ENG (il cursore deve essere lì)
2. Clicca sul placeholder desiderato
3. Il testo viene inserito nella posizione del cursore

Premi **Salva impostazioni** per memorizzare messaggi, orari e capienza generale.

---

## Export prenotazioni giorno

1. Seleziona la **data da esportare**
2. Scegli il formato:
   - **Esporta CSV** — file tabellare per Excel/Sheets
   - **Esporta PDF** — apre la finestra di stampa del browser (salva come PDF)

Colonne esportate: Nome, Persone, Telefono, Data, Ora, Stato, Note.

---

## Report totali per periodo

1. Imposta **Dal** e **Al**
2. Scegli il **filtro stato** (tutti, da confermare, confermate, annullate, no-show)
3. Clicca **Genera report**

Il report mostra:
- Prenotazioni totali e persone totali
- Conteggio per stato (confermate, da confermare, annullate, no-show)
- Tasso annullate e tasso no-show (%)

**Report PDF** — stampa/salva il report appena generato.

---

## Salvataggio e protezione dati

L'agenda usa **GitHub come database**: i file JSON vivono nel repository e vengono aggiornati tramite API.

| File | Contenuto |
|------|-----------|
| `data/prenotazioni.json` | Tutte le prenotazioni |
| `data/impostazioni.json` | Impostazioni (capienza, orari, messaggi) |

### Backup

- Ogni salvataggio crea un **commit** su GitHub
- Puoi ripristinare versioni precedenti da GitHub → file → **History**

### Migrazione da versione precedente

Se avevi dati nel browser (localStorage), alla prima connessione GitHub vengono **importati automaticamente** nei file JSON del repository.

### Importante

- Configura il token GitHub prima di usare l'agenda
- Il token è personale: non condividerlo
- Su un nuovo dispositivo, reinserisci il token nelle Impostazioni
- Il **tema chiaro/scuro** resta nel browser (preferenza visiva)
- **Non usare "Svuota tutto"** se non vuoi perdere tutte le prenotazioni

---

## Consigli d'uso quotidiano

1. **Mattina** — apri con filtro **Oggi**, controlla i posti occupati
2. **Nuova chiamata** — inserisci subito la prenotazione; il sistema blocca se sei pieno
3. **Conferma** — usa 🇮🇹 o 🇬🇧 per inviare WhatsApp; poi segna **OK** quando il cliente conferma
4. **No-show** — segna 👁️❌ dopo il servizio per statistiche corrette
5. **Weekend/eventi** — imposta capienza specifica per quei giorni in anticipo
6. **Fine settimana** — esporta CSV o PDF per archivio

---

## Risoluzione problemi

| Problema | Soluzione |
|----------|-----------|
| Non vedo le prenotazioni | Controlla il filtro (Oggi/Domani/Tutte). Prova **Reset** |
| Server non raggiungibile | Non serve server: configura Database GitHub nelle Impostazioni |
| Token non valido | Rigenera il token con permesso Contents Read/Write sul repo |
| Prenotazioni sparite | Controlla `data/prenotazioni.json` su GitHub o la cronologia del file |
| WhatsApp non si apre | Verifica il numero (solo cifre, con prefisso paese es. 39...) |
| Export PDF non funziona | Abilita i popup nel browser |
| Capienza sbagliata | Controlla se c'è un override per quel giorno in Impostazioni |
| Dati su un altro PC | Apri l'agenda e inserisci lo stesso token GitHub |

---

## Requisiti tecnici

- Browser moderno (Chrome, Safari, Firefox, Edge)
- Account GitHub con accesso al repository
- Token GitHub con permesso scrittura sui file del repo
- JavaScript abilitato
- Per WhatsApp: app WhatsApp installata sul telefono (da mobile) o WhatsApp Web (da PC)

---

## Versione

Documentazione aggiornata alle funzionalità con:
- Placeholder messaggi cliccabili (`{name}`, `{people}`, `{date}`, `{time}`, `{phone}`, `{notes}`)
- Migrazione e backup dati
- Capienza massima generale e per singolo giorno
- Export CSV/PDF e report periodo
- Tema chiaro/scuro
