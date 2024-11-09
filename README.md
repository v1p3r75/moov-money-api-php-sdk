# Moov Money PHP SDK

**Moov Money PHP SDK** est une bibliothèque qui permet aux développeurs d’intégrer facilement l’API de Moov Money dans leurs applications PHP sans avoir à manipuler directement les requêtes SOAP. Cette documentation fournit une vue d’ensemble des configurations et des fonctionnalités principales du SDK, accompagnée d’exemples de code.

## Table des Matières

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Fonctionnalités](#fonctionnalités)
    - [Envoyer une Transaction Push](#1-envoyer-une-transaction-push)
    - [Envoyer une Transaction Push avec Statut en Attente](#2-envoyer-une-transaction-push-avec-statut-en-attente)
    - [Vérifier le Statut d'une Transaction](#3-vérifier-le-statut-dune-transaction)
    - Transférer des fonds depuis le compte du marchand vers un autre compte autorisé
    - Vérifier le solde actuel d’un compte abonné, principalement le solde principal
    - Récupèrer le statut du compte mobile d’un abonné
    - Effectuer des transactions de dépôt d'argent (cash-in) vers un abonné
    - Effectuer des transactions de rechargement de crédit téléphonique pour un abonné
4. [Gestion des réponses](#gestion-des-réponses)
5. [Gestion des erreurs](#gestion-des-erreurs)
6. [Les Todos](#les-tâches-à-réaliser-todos)
7. [Contribution](#contribution)
8. [Développeur](#développeurs)

## Installation

Installez le SDK via Composer en exécutant la commande suivante :

```bash
composer require v1p3r75/moov-money-api-sdk
```

## Configuration

Avant d’utiliser le SDK, configurez les identifiants de connexion, l’URL de l’API, le timeout pour les requêtes, clé de chiffrements, etc.
Le SDK inclut une classe `MoovMoneyAPIConfig` pour simplifier cette configuration.

```php
<?php

use MoovMoney\MoovMoneyAPI;
use MoovMoney\MoovMoneyAPIConfig;

$config = new MoovMoneyAPIConfig();
$config->setUsername('your_username')
       ->setPassword('your_password')
       ->setRequestTimeout(30) // en secondes
       ->setBaseUrl('https://api.server.com/');

$moovApi = new MoovMoneyAPI($config);

```

### Détails des Options de Configuration



| Option            | Type     | Description                                                                                  |
|-------------------|----------|----------------------------------------------------------------------------------------------|
| `username`        | `string` | Nom d'utilisateur fourni par Moov Money.                                                     |
| `password`        | `string` | Mot de passe pour l'authentification de l'API Moov Money.                                    |
| `baseUrl`         | `string` | URL de l'API Moov Money.                                                                     |
| `encryptionKey`   | `string` | Clé de chiffrement pour générer les tokens d'authentification (32 caractères pour AES-256). Le SDK utilise la clé fournie par Moov : `tlc12345tlc12345tlc12345tlc12345`. Vous ne devez pas modifier cette clé, sauf si Moov en fournit une nouvelle.  |
| `requestTimeout`  | `float`  | Durée maximale (en secondes) pour les requêtes HTTP (60 par défaut).                                         |

## Fonctionnalités

Le SDK propose quelques fonctionnalités :

- Envoyer une transaction push (`pushTransaction`).
- Envoyer une transaction push avec statut en attente (`pushWithPendingTransaction`).
- Vérifier le statut d'une transaction (`getTransactionStatus`).
- Transférer des fonds depuis le compte du marchand vers un autre compte autorisé (`transfertFlooz`).
- Vérifier le solde actuel d’un compte abonné, principalement le solde principal (`getBalance`).
- Récupèrer le statut du compte mobile d’un abonné, y compris les informations KYC (Know Your Customer).
- Effectuer des transactions de dépôt d'argent (cash-in) vers un abonné.
- Effectuer des transactions de rechargement de crédit téléphonique pour un abonné.

### 1. Envoyer une Transaction Push

La méthode pushTransaction envoie une demande de paiement au client via une transaction push.

```php
<?php

$response = $moovApi->pushTransaction(
    telephone: '22995901234',
    amount: 5000,
    message: 'Paiement de 5000 FCFA',
    data1: 'Order_1234', // facultatif
    data2: 'Additional info', // facultatif
    fee: 0 // frais facultatif
);

```
#### Paramètres :

- `telephone` : Numéro de téléphone du client (`string`).
- `amount` : Montant de la transaction (`int`).
- `message` : Message envoyé au client pour la transaction (`string`).
- `data1` et `data2` : Données additionnelles facultatives (`string`).
- `fee` : Montant des frais de transaction (`int`). Par défaut est à 0.

### 2. Envoyer une Transaction Push avec Statut en Attente

La méthode pushWithPendingTransaction envoie une demande de transaction push qui reste en attente jusqu’à confirmation du client. Ce dernier peut confirmer la transaction après grâce à un code USSD.

```php
<?php
$response = $moovApi->pushWithPendingTransaction(
    telephone: '22995181019',
    amount: 5000,
    message: 'Paiement de 5000 FCFA',
    data1: 'Order_1234',
    data2: 'Additional info',
    fee: 0
);
```
#### Paramètres : Identiques à `pushTransaction`.

### 3. Vérifier le Statut d'une Transaction

La méthode getTransactionStatus permet de vérifier le statut d'une transaction existante en fournissant son identifiant de référence.

```php
<?php

$statusResponse = $moovApi->getTransactionStatus('72024103000000009');

```
#### Paramètres :

- `referenceId` : Identifiant de la transaction dont on souhaite vérifier le statut (`string`).

## Gestion des réponses

La classe `MoovMoneyApiResponse` dans le SDK Moov Money encapsule les réponses de l'API pour fournir une interface simple et cohérente aux développeurs. Cette classe permet d'accéder aux données de la réponse sous forme d'objet, offrant des méthodes pour extraire les informations les plus importantes sur la transaction.

### Description des Méthodes

Voici un aperçu des méthodes principales de `MoovMoneyApiResponse` :

- `getStatusCode()` :
Retourne le code de statut de la réponse en tant qu'entier. Ce code indique le statut de la transaction (par exemple, succès, en attente, échec). C'est à `0` quand tout est bon.

- `getReferenceId()` :
Retourne l'identifiant de référence de la transaction, qui est unique pour chaque transaction. Il est utile pour effectuer des vérifications ou des suivis de transactions.

- `getDescription()` :
Retourne une description courte de la transaction ou de l'erreur, telle que fournie par l'API. Cette description donne une indication rapide de la réponse, par exemple si la transaction a été réussie ou échouée.

- `getTransactionData()` :
Retourne les données supplémentaires associé à la transaction lors d'un push (transid).

- `getLongDescription() `:
Retourne une description détaillée du statut, obtenue via la classe `ApiStatus`. Cela permet de convertir un code de statut en message explicatif pour faciliter le débogage ou l’affichage d’informations plus claires à l’utilisateur final.

- `toArray()` :
Convertit la réponse en un tableau associatif, en renvoyant toutes les données contenues dans la réponse. Cela est particulièrement utile pour le débogage ou l'enregistrement des réponses de l'API.

- `get(string $key)` :
Cette méthode générique permet d'accéder directement à une valeur spécifique dans la réponse en utilisant sa clé. Par exemple, pour accéder au champ status : `$response->get('status')`

## Gestion des erreurs

Les erreurs envoyées par l'API Moov Money sont levées sous forme d'exceptions, comportant le message d'erreur associé, via la classe `ServerErrorException::class`.

## Les tâches à réaliser (Todos)

- [x] Push Transaction : Envoyer une transaction push.
- [x] Push With Pending Transaction : Envoyer une transaction push avec statut en attente.
- [x] Transaction Status : Vérifier le statut d'une transaction.
- [x] TransferFlooz : transférer des fonds depuis le compte du marchand vers un autre compte autorisé.
- [x] GetBalance : vérifier le solde actuel d’un compte abonné, principalement le solde principal.
- [ ] GetMobileStatus : récupèrer le statut du compte mobile d’un abonné, y compris les informations KYC (Know Your Customer).
- [ ] Cash In Transactions : effectuer des transactions de dépôt d'argent (cash-in) vers un abonné.
- [ ] Airtime Transactions : effectuer des transactions de rechargement de crédit téléphonique pour un abonné.



## Contribution

Les contributions sont les bienvenues ! Pour signaler un bug ou proposer des fonctionnalités, veuillez soumettre une issue ou une pull request. [Plus sur comment contributer](./CONTRIBUTING.md).

## Développeurs

- [Fortunatus KIDJE - @v1p3r75](https://github.com/v1p3r75) (Développeur principal)
