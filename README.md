# PhysiMonitor - Software Requirements Specification

![Status](https://img.shields.io/badge/Status-Version%201.0-brightgreen)
![University](https://img.shields.io/badge/University-Luigi%20Vavnitelli-orange)
![Database](https://img.shields.io/badge/Database-MySQL-blue)

## 👤 Autore
* **Elio Fava** (A13002167)

**Università degli Studi della Campania "Luigi Vanvitelli"** **Corso:** Ingegneria Informatica  
**Documento:** Software Requirements Specification (SRS)

---

## 📝 Descrizione del Progetto
**PhysiMonitor** è un sistema software progettato per il monitoraggio dei parametri fisici e degli allenamenti. L'obiettivo principale è fornire agli utenti uno strumento efficace per tracciare i propri progressi fisici (peso, altezza) e gestire le proprie routine di allenamento in modo organizzato.

Il sistema gestisce l'autenticazione sicura e la persistenza dei dati tramite un database relazionale.

---

## 🚀 Funzionalità Principali
Il sistema offre le seguenti capacità:

* **Gestione Profilo:** Registrazione e autenticazione utenti con tracciamento di altezza, peso attuale e peso desiderato.
* **Monitoraggio Peso:** Storico delle variazioni di peso nel tempo con associazione temporale (data).
* **Gestione Allenamenti:** Organizzazione degli esercizi per gruppi muscolari e gestione delle schede di allenamento personalizzate.
* **Social Tracking:** Funzionalità per seguire altri utenti e monitorare i progressi all'interno della community.

---

## 📊 Modellazione dei Dati e UML
Il progetto segue una rigorosa progettazione software documentata nel SRS:

### 1. Casi d'Uso
Analisi delle interazioni tra l'utente e il sistema per le fasi di registrazione, inserimento dati fisici e consultazione allenamenti.

### 2. Progettazione Concettuale (Schema ER)
Il database è strutturato per gestire le relazioni tra:
* **Users:** Dati anagrafici e credenziali.
* **Peso:** Storico delle misurazioni.
* **Allenamenti:** Dettagli sulle sessioni di esercizio fisico.

---

## 📂 Struttura del Repository
All'interno del repository sono presenti le seguenti sezioni:

* **`swbd/`**: Contiene il codice sorgente completo e gli script per la gestione del database (Back-end e Front-end).
* **Documentazione**: Il file `Software Requirements for PhysiMonitor.pdf` con le specifiche tecniche complete.

---

## 🛠️ Tecnologie utilizzate
* **Linguaggi:** SQL (per la gestione del DB), HTML/PHP (per l'interfaccia e la logica).
* **Database:** MySQL.
* **Modellazione:** UML e Schemi E-R.
