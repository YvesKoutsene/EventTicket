# Plateforme de Billetterie - Yentim Solux

---

## Contexte
Projet développé lors de mon **stage de fin de licence** au **Cabinet Yentim Solux** (Lomé, Togo) de mai à août 2024.  
L'objectif était de créer une **plateforme complète de billetterie** avec deux interfaces :
- **Interface web** pour les administrateurs (gestion des événements, billetterie, rapports)
- **Application mobile** pour les participants (consultation, achat de billets)

---

## Objectifs du projet
- Concevoir et modéliser une **base de données relationnelle** robuste pour la billetterie
- Développer les **requêtes SQL analytiques** pour le suivi des ventes
- Assurer la **cohérence et l'intégrité des données** transactionnelles
- Rédiger un **mémoire de fin d'études** (60 pages) documentant l'analyse des besoins et la conception

---

## Technologies utilisées

| Domaine | Technologies |
|---------|-------------|
| **Base de données** | PostgreSQL / MySQL |
| **Backend** | Laravel (PHP) |
| **Frontend Web** | HTML/CSS, JavaScript |
| **Mobile** | Flutter |
| **API** | RESTful |
| **Outils** | Git, GitHub, Trello, UML |

---

## Base de données - Modélisation

### Schéma relationnel
![Schéma BDD](docs/schema-billetterie.png)

**Tables principales :**
- `evenements` : informations sur les événements (nom, date, lieu, capacité)
- `participants` : données des utilisateurs (nom, email, téléphone)
- `billets` : types de billets (tarif, catégorie, quantité disponible)
- `commandes` : transactions d'achat (date, montant, statut)
- `achats` : association entre commandes et billets (quantité, prix unitaire)
- `paiements` : informations de paiement (mode, statut, référence)
- etc.

### Contraintes d'intégrité
```sql
-- Exemple de clé étrangère avec contrainte
ALTER TABLE commandes
ADD CONSTRAINT fk_commande_participant
FOREIGN KEY (participant_id) REFERENCES participants(id)
ON DELETE RESTRICT;
