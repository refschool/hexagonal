# Prompt Codex — Mini e-commerce PHP en architecture hexagonale

## Objectif

Construire une petite application Web e-commerce en **PHP pur**, sans framework applicatif, pour enseigner l'architecture hexagonale.

Le projet doit être réalisé **progressivement en 9 étapes**.

À la fin de **chaque étape** :

1. vérifier que le code fonctionne ;
2. exécuter les tests disponibles ;
3. mettre à jour la documentation si nécessaire ;
4. faire un `git status` ;
5. créer un commit Git avec le message imposé ;
6. créer un tag Git `step-01`, `step-02`, etc. ;
7. ne jamais regrouper plusieurs étapes dans un même commit.

Ne jamais pousser vers un dépôt distant sauf instruction explicite.

---

# Contraintes globales

## Technique

- PHP 8.3+.
- PHP pur, **aucun framework** : pas de Laravel, Symfony, Slim, etc.
- Composer autorisé pour l'autoload PSR-4 et éventuellement PHPUnit en développement.
- Utiliser `declare(strict_types=1);`.
- Respecter PSR-12 autant que possible.
- Utiliser des classes typées.
- Pas d'ORM.
- Pas de base SQL.
- Persistance métier via adapters **InMemory**.
- Serveur Web :
  ```bash
  php -S localhost:8000 -t public
  ```
- HTML simple, sans framework CSS.
- JavaScript non requis.

## Architecture

Séparer clairement :

```text
Domain
Application
Ports
Adapters
Composition Root
```

Règle essentielle :

> Le domaine et les cas d'utilisation ne dépendent jamais de HTTP, HTML, session, SQL ou d'un framework.

Les dépendances pointent vers le cœur :

```text
Adapters -> Ports/Application -> Domain
```

Jamais l'inverse.

---

# Domaine fonctionnel

Mini e-commerce avec :

- lister les produits ;
- afficher un produit ;
- ajouter un produit au panier ;
- modifier une quantité ;
- supprimer un produit du panier ;
- afficher le panier ;
- créer une commande ;
- lister les commandes ;
- afficher une commande.

Ne pas implémenter :

- authentification ;
- paiement réel ;
- promotions ;
- livraison ;
- stock avancé ;
- administration ;
- API externe.

---

# Modèle métier

## Product

Propriétés :

```text
id
name
price
```

Règles :

- nom non vide ;
- prix strictement positif.

## Money

Créer un Value Object simple représentant un montant en centimes.

Exemple :

```text
7900 = 79,00 €
```

## CartItem

```text
product
quantity
```

Règle : quantité >= 1.

## Cart

Responsabilités :

```text
addProduct()
removeProduct()
updateQuantity()
items()
isEmpty()
total()
clear()
```

Si un produit déjà présent est ajouté une seconde fois, augmenter la quantité.

## OrderItem

Conserver au minimum :

```text
productId
productName
unitPrice
quantity
lineTotal
```

## Order

Propriétés :

```text
id
items
total
status
createdAt
```

Statut initial :

```text
CREATED
```

Une commande ne peut pas être créée depuis un panier vide.

---

# Arborescence cible

```text
mini-shop/
│
├── composer.json
├── README.md
├── public/
│   └── index.php
│
├── src/
│   ├── Domain/
│   │   ├── Product.php
│   │   ├── Money.php
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   │
│   ├── Application/
│   │   ├── ListProducts.php
│   │   ├── GetProduct.php
│   │   ├── AddProductToCart.php
│   │   ├── RemoveProductFromCart.php
│   │   ├── UpdateCartQuantity.php
│   │   ├── ViewCart.php
│   │   ├── CreateOrder.php
│   │   ├── ListOrders.php
│   │   └── GetOrder.php
│   │
│   ├── Port/
│   │   ├── In/
│   │   └── Out/
│   │       ├── ProductRepository.php
│   │       ├── CartRepository.php
│   │       └── OrderRepository.php
│   │
│   └── Adapter/
│       ├── In/
│       │   └── Web/
│       │       ├── ProductController.php
│       │       ├── CartController.php
│       │       └── OrderController.php
│       │
│       └── Out/
│           └── InMemory/
│               ├── InMemoryProductRepository.php
│               ├── InMemoryCartRepository.php
│               └── InMemoryOrderRepository.php
│
├── templates/
│   ├── layout.php
│   ├── products/
│   ├── cart/
│   └── orders/
│
└── tests/
    ├── Domain/
    ├── Application/
    └── Adapter/
```

---

# Remarque importante : InMemory et HTTP

Un objet PHP InMemory est recréé à chaque requête HTTP.

Pour conserver un TP Web exploitable sans SQL :

- `InMemoryProductRepository` est recréé avec un catalogue fixe à chaque requête ;
- panier et commandes restent accessibles derrière les ports `CartRepository` et `OrderRepository` ;
- la couche Adapter peut réhydrater l'état depuis `$_SESSION` au début d'une requête et le sauvegarder à la fin ;
- **aucune référence à `$_SESSION` ne doit apparaître dans Domain ou Application**.

Documenter clairement ce choix dans le README.

---

# Étape 1 — Initialiser le projet

## Objectif
Créer le squelette technique sans logique métier.

## À faire

- initialiser Git si nécessaire ;
- créer `composer.json` ;
- configurer PSR-4 :
  ```json
  {
    "autoload": {
      "psr-4": {
        "App\\": "src/"
      }
    }
  }
  ```
- créer l'arborescence ;
- créer `public/index.php` avec une réponse minimale ;
- créer `README.md` et `.gitignore` ;
- exécuter :
  ```bash
  composer dump-autoload
  php -l public/index.php
  ```
- vérifier :
  ```bash
  php -S localhost:8000 -t public
  ```

## Critère de réussite
`http://localhost:8000` affiche une page minimale.

## Commit

```bash
git add .
git commit -m "chore: bootstrap pure PHP hexagonal project"
git tag step-01
```

---

# Étape 2 — Créer le domaine

## Objectif
Créer les objets métier, sans Web ni repository.

## À faire

Créer :

```text
Product
Money
CartItem
Cart
OrderItem
Order
```

Ne pas ajouter :

- HTTP ;
- session ;
- repository ;
- HTML.

Créer des tests unitaires si PHPUnit est disponible.

## Critère de réussite
Les objets métier sont instanciables indépendamment de toute infrastructure.

## Commit

```bash
git add .
git commit -m "feat(domain): add ecommerce domain model"
git tag step-02
```

---

# Étape 3 — Implémenter les règles métier

## Objectif
Faire vivre le domaine.

## Règles

### Product
- nom non vide ;
- prix > 0.

### Cart
- ajouter un produit ;
- fusionner les quantités ;
- refuser quantité < 1 ;
- modifier une quantité ;
- supprimer un produit ;
- calculer le total ;
- détecter panier vide ;
- vider le panier.

### Order
- interdire une commande sans article ;
- figer les données dans `OrderItem` ;
- calculer le total ;
- statut initial `CREATED`.

## Critère de réussite
Les règles métier sont testables sans repository, serveur Web ni session.

## Commit

```bash
git add .
git commit -m "feat(domain): implement ecommerce business rules"
git tag step-03
```

---

# Étape 4 — Créer les ports de sortie

## Objectif
Définir les besoins du cœur vis-à-vis de l'extérieur.

## ProductRepository

```text
findAll()
findById(id)
```

## CartRepository

```text
get()
save(cart)
```

## OrderRepository

```text
nextIdentity()
save(order)
findAll()
findById(id)
```

Aucune référence à :

```text
PDO
SQL
Session
HTTP
HTML
```

## Commit

```bash
git add .
git commit -m "feat(ports): define repository output ports"
git tag step-04
```

---

# Étape 5 — Créer les adapters InMemory

## Objectif
Implémenter les ports sans base de données.

Créer :

```text
InMemoryProductRepository
InMemoryCartRepository
InMemoryOrderRepository
```

Catalogue initial :

```text
1 - Clavier mécanique - 79,00 €
2 - Souris sans fil - 39,00 €
3 - Écran 27 pouces - 249,00 €
4 - Webcam HD - 59,00 €
```

Tester :

- `findAll()` ;
- `findById()` ;
- sauvegarde du panier ;
- sauvegarde d'une commande ;
- récupération d'une commande.

## Commit

```bash
git add .
git commit -m "feat(adapters): add in-memory persistence adapters"
git tag step-05
```

---

# Étape 6 — Créer les cas d'utilisation

## Cas d'utilisation

```text
ListProducts
GetProduct
AddProductToCart
RemoveProductFromCart
UpdateCartQuantity
ViewCart
CreateOrder
ListOrders
GetOrder
```

## AddProductToCart

```text
1. recevoir productId et quantity
2. récupérer Product via ProductRepository
3. gérer le produit absent
4. récupérer Cart via CartRepository
5. appeler Cart::addProduct()
6. sauvegarder Cart
7. retourner le résultat
```

## CreateOrder

```text
1. récupérer le panier
2. vérifier qu'il n'est pas vide
3. demander un id à OrderRepository
4. créer Order
5. sauvegarder Order
6. vider le panier
7. sauvegarder le panier
8. retourner la commande
```

Les use cases ne connaissent jamais :

```text
$_GET
$_POST
$_SESSION
HTML
PDO
SQL
```

Tester les use cases avec les adapters InMemory.

## Commit

```bash
git add .
git commit -m "feat(application): add ecommerce use cases"
git tag step-06
```

---

# Étape 7 — Créer l'adapter Web

## Routes

```text
GET  /
GET  /products
GET  /products/{id}

GET  /cart
POST /cart/add
POST /cart/update
POST /cart/remove

POST /orders
GET  /orders
GET  /orders/{id}
```

Créer :

```text
ProductController
CartController
OrderController
```

Flux :

```text
HTTP Request
    ↓
extraire paramètres
    ↓
Use Case
    ↓
résultat
    ↓
View / Redirect
```

Les controllers ne font pas de logique métier.

Gérer au minimum :

```text
404 produit absent
404 commande absente
400 quantité invalide
400 panier vide
```

## Commit

```bash
git add .
git commit -m "feat(web): add HTTP input adapters and routing"
git tag step-07
```

---

# Étape 8 — Créer les vues HTML

## Pages

### Produits
- nom ;
- prix ;
- détail ;
- ajout panier.

### Détail produit
- nom ;
- prix ;
- quantité ;
- bouton ajouter.

### Panier
- produit ;
- prix unitaire ;
- quantité ;
- total ligne ;
- total panier ;
- modifier ;
- supprimer ;
- créer commande.

### Commandes
- identifiant ;
- date ;
- statut ;
- total ;
- détail.

Créer un layout :

```text
MiniShop
Produits | Panier | Commandes
```

Pas de framework CSS.

## Critère de réussite
Le scénario complet fonctionne dans le navigateur.

## Commit

```bash
git add .
git commit -m "feat(web): add HTML views for shop workflow"
git tag step-08
```

---

# Étape 9 — Composition Root et validation finale

## Objectif
Assembler proprement les dépendances.

Créer un emplacement unique pour instancier :

```text
Repositories
    ↓
Use Cases
    ↓
Controllers
```

Exemple conceptuel :

```php
$productRepository = new InMemoryProductRepository(...);
$cartRepository = new InMemoryCartRepository(...);
$orderRepository = new InMemoryOrderRepository(...);

$listProducts = new ListProducts($productRepository);

$addProductToCart = new AddProductToCart(
    $productRepository,
    $cartRepository
);

$createOrder = new CreateOrder(
    $cartRepository,
    $orderRepository
);
```

Domain et Application ne doivent jamais instancier un adapter concret.

## Scénario final

```text
1. afficher les produits
2. ouvrir un produit
3. ajouter 2 unités au panier
4. ajouter un second produit
5. afficher le panier
6. modifier une quantité
7. vérifier le total
8. créer la commande
9. vérifier que le panier est vide
10. afficher les commandes
11. afficher le détail de la commande
```

## Vérifications

```bash
composer dump-autoload
```

Exécuter tous les tests puis, si disponible :

```bash
find src public tests -name "*.php" -print0 | xargs -0 -n1 php -l
```

## README final

Expliquer :

```text
Domain
Application
Ports
Adapters
Composition Root
```

Ajouter ce diagramme :

```text
                Browser
                   |
                   v
             Web Adapter
                   |
                   v
               Use Cases
                   |
          +--------+--------+
          |                 |
          v                 v
 ProductRepository    CartRepository
          ^                 ^
          |                 |
     InMemory Adapter  InMemory Adapter
```

Questions pédagogiques :

> Que modifier pour remplacer HTML par une API JSON ?

Réponse : ajouter/remplacer un **Input Adapter**.

> Que modifier pour remplacer InMemory par SQL ?

Réponse : ajouter de nouveaux **Output Adapters** implémentant les mêmes ports.

Domain et Application doivent rester inchangés.

## Commit final

```bash
git add .
git commit -m "feat(app): wire hexagonal application and complete shop workflow"
git tag step-09
```

---

# Vérification Git finale

Afficher :

```bash
git log --oneline --decorate --graph --all
```

Historique attendu :

```text
step-01  chore: bootstrap pure PHP hexagonal project
step-02  feat(domain): add ecommerce domain model
step-03  feat(domain): implement ecommerce business rules
step-04  feat(ports): define repository output ports
step-05  feat(adapters): add in-memory persistence adapters
step-06  feat(application): add ecommerce use cases
step-07  feat(web): add HTTP input adapters and routing
step-08  feat(web): add HTML views for shop workflow
step-09  feat(app): wire hexagonal application and complete shop workflow
```

---

# Règles de comportement pour Codex

1. Travailler étape par étape, dans l'ordre.
2. Ne pas anticiper une étape ultérieure sauf nécessité technique.
3. Garder le code simple et pédagogique.
4. Ne jamais introduire de framework.
5. Ne pas introduire de pattern supplémentaire sans nécessité.
6. Ne jamais mettre la logique métier dans les controllers.
7. Le domaine ne dépend jamais des repositories concrets.
8. Écrire du code lisible par des étudiants.
9. Commenter uniquement quand la valeur pédagogique est réelle.
10. Après chaque étape, fournir :
    - fichiers créés/modifiés ;
    - tests effectués ;
    - commit créé ;
    - tag créé.
11. Si une étape échoue, la corriger avant le commit.
12. Ne jamais exécuter `git push`.

---

# Résultat pédagogique attendu

L'étudiant doit pouvoir expliquer :

```text
Browser
   |
   v
Web Adapter
   |
   v
Use Case
   |
   v
Domain
   |
   v
Output Port
   |
   v
InMemory Adapter
```

Et répondre à :

> Pourquoi peut-on remplacer l'interface Web ou la persistance sans réécrire la logique métier ?

Parce que le cœur dépend d'abstractions et non des détails techniques.
