# Gestion des dettes — Complexe Saint Joseph

Ce module est actuellement autonome : l’espace de travail ne contient ni connexion à la base commerciale existante ni schéma Snack/Cave à reprendre. Le schéma ci-dessous crée une nouvelle base `complexe_saint_joseph` ; il ne migre et ne modifie aucune base inconnue.

## Mise en service sous XAMPP

1. Démarrer Apache et MySQL depuis XAMPP.
2. Dans phpMyAdmin, importer `schema.sql`. Il crée les tables `customers`, `debts`, `payments`, `debt_reset_history` et `debt_change_history` avec leurs clés étrangères. Pour une base déjà existante, exécuter aussi `migrate_voided_debts.sql` une seule fois afin d’ajouter les colonnes d’annulation.
3. Activer `pdo_mysql` dans le `php.ini` utilisé par Apache et redémarrer Apache. Avec XAMPP, vérifier que `extension=pdo_mysql` est activée dans `C:\xampp\php\php.ini`.
4. Définir les variables d’environnement suivantes pour Apache, puis redémarrer Apache :

```apache
SetEnv CSJ_DB_HOST "127.0.0.1"
SetEnv CSJ_DB_NAME "complexe_saint_joseph"
SetEnv CSJ_DB_USER "root"
SetEnv CSJ_DB_PASSWORD ""
SetEnv CSJ_ADMIN_NAME "Administrateur"
SetEnv CSJ_ADMIN_PASSWORD "remplacer-par-un-secret-long"
```

Ne pas conserver le mot de passe d’exemple en production. L’accès à la réinitialisation est désactivé tant que `CSJ_ADMIN_PASSWORD` n’est pas défini. L’API utilise les variables `CSJ_DB_*` pour se connecter.

5. Ouvrir `http://localhost/dette%20complex/`.

## Agent IA local

L’agent utilise Ollama en local. Aucune clé API n’est requise et les téléphones des clients ne sont jamais transmis au modèle. Installez [Ollama pour Windows](https://ollama.com/download/windows) et démarrez l’application, puis ouvrez PowerShell :

```powershell
ollama pull qwen2.5:3b
```

L’application attend par défaut l’API locale `http://127.0.0.1:11434` et le modèle `qwen2.5:3b`. L’application Ollama doit être démarrée pendant l’utilisation. Pour choisir un autre modèle, définir `CSJ_OLLAMA_MODEL` dans l’environnement Apache puis redémarrer Apache. L’URL `CSJ_OLLAMA_URL` peut être configurée uniquement vers `localhost` ou `127.0.0.1` afin de garder les données sur cette machine.

L’agent peut résumer les dettes, repérer les retards, préparer un message de relance à copier ou préremplir une proposition de dette/paiement. Il n’enregistre rien et n’envoie aucun message : la dette ou le paiement doit être vérifié et validé dans le formulaire normal. Les détails transmis au modèle excluent les numéros de téléphone.

## Fonctionnement

- La vue Dettes contient les seules dettes actives ; l’historique conserve aussi les dettes payées et archivées.
- « Nouvelle Facture / Dette » crée une créance. Le bouton « Facture » ouvre une facture imprimable, à enregistrer en PDF depuis le navigateur.
- « Rapport PDF » sur l’écran Dettes exporte la vue active avec ses filtres ; le rapport de l’historique conserve aussi les dettes annulées et indique leur motif.
- « Supprimer » annule une dette impayée avec motif et piste d’audit ; la ligne reste dans l’historique. Une dette avec paiement ne peut pas être supprimée par ce raccourci.
- « Modifier » permet de corriger une dette active. Chaque changement conserve les anciennes et nouvelles valeurs dans `debt_change_history`; les dettes archivées ne sont pas modifiables et le montant ne peut pas passer sous le total payé.
- Le nom du client est obligatoire ; le téléphone est facultatif et peut rester vide.
- « Archiver le client » retire une fiche de la liste active sans effacer le client, ses coordonnées ou son historique. Les clients ayant un solde actif dû ne peuvent pas être archivés ; la vue Clients permet d’afficher les archives.
- La réinitialisation archive les dettes actives et leurs paiements, sans les supprimer. Le total, l’utilisateur et l’horodatage sont inscrits dans `debt_reset_history`.
- Les paiements sont validés dans une transaction avec verrouillage de la dette et refus du surpaiement ou d’un paiement sur dette archivée.
- Les modes MTN Mobile Money et Orange Money demandent une référence de transaction. Le transfert doit être confirmé sur le téléphone avant son enregistrement ; cette version ne déclenche pas de demande de paiement opérateur.
- « Exporter en PDF » ouvre la boîte d’impression du navigateur ; choisir « Enregistrer au format PDF ». Les filtres actifs sont repris dans le rapport.

Avant de connecter ce module à une base commerciale déjà utilisée, effectuer une sauvegarde et définir explicitement la stratégie de migration et de rapprochement des clients. Aucun import automatique depuis les systèmes Snack/Cave n’est possible tant que leur schéma et leurs accès ne sont pas fournis.