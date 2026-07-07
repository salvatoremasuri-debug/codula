# Agenda Prenotazioni Ristorante — Guida completa

Applicazione web leggera per gestire le prenotazioni del ristorante direttamente dal browser (telefono, tablet o PC).  
Non serve installare nulla: i dati restano salvati nel browser del dispositivo che usi.

---

## Link per aprire l'agenda

### Opzione consigliata — GitHub Pages (se attivato)

https://salvatoremasuri-debug.github.io/codula/

> Se il link non funziona ancora, attiva GitHub Pages dal repository:  
> **Settings → Pages → Branch: `main` → Folder: `/ (root)` → Save**  
> Dopo 1–2 minuti l'agenda sarà online a quell'indirizzo.

### Opzione immediata — Anteprima HTML (funziona subito)

https://htmlpreview.github.io/?https://raw.githubusercontent.com/salvatoremasuri-debug/codula/main/index.html

### Opzione locale — Dal file sul PC

1. Scarica `index.html` dal repository
2. Fai doppio clic sul file
3. Si apre nel browser predefinito

**Repository:** https://github.com/salvatoremasuri-debug/codula

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
| **Sicurezza dati** | Salvataggio separato prenotazioni/impostazioni + backup automatico |

---

## Schermata principale

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
| Orario | Sì | |
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

Premi **Salva impostazioni** per memorizzare messaggi e capienza generale.

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

L'agenda salva tutto nel **localStorage** del browser, in due archivi separati:

| Chiave | Contenuto |
|--------|-----------|
| `agenda_prenotazioni_main` | Tutte le prenotazioni |
| `agenda_impostazioni_main` | Impostazioni (capienza, messaggi, capienze giornaliere) |

### Backup automatico

Prima di ogni salvataggio viene creato un backup:

| Backup | Contenuto |
|--------|-----------|
| `agenda_prenotazioni_backup` | Copia precedente delle prenotazioni |
| `agenda_impostazioni_backup` | Copia precedente delle impostazioni |

### Migrazione automatica

All'avvio, se i dati non sono nel formato nuovo, l'app prova a recuperarli dalle versioni precedenti (`v1`, `v2`, ecc.) e dal backup.

### Importante

- I dati sono **legati al browser e al dispositivo** che usi
- Se cambi browser, cancelli i dati del sito o usi la modalità privata, le prenotazioni non ci sono
- Per sicurezza, esporta periodicamente in CSV/PDF i giorni importanti
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
| Prenotazioni sparite dopo aggiornamento | Ricarica la pagina: la migrazione parte all'avvio. Se erano già cancellate dal browser, non recuperabili |
| WhatsApp non si apre | Verifica il numero (solo cifre, con prefisso paese es. 39...) |
| Export PDF non funziona | Abilita i popup nel browser |
| Capienza sbagliata | Controlla se c'è un override per quel giorno in Impostazioni |
| Dati su un altro telefono | I dati non si sincronizzano: esporta e importa manualmente (CSV) o usa sempre lo stesso dispositivo |

---

## Requisiti tecnici

- Browser moderno (Chrome, Safari, Firefox, Edge)
- Connessione internet solo per il primo caricamento della pagina
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
