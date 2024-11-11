# Moov Money PHP SDK

**Moov Money PHP SDK** est une bibliothèque qui permet aux développeurs d’intégrer facilement l’API de Moov Money dans leurs applications PHP sans avoir à manipuler directement les requêtes SOAP. Cette documentation fournit une vue d’ensemble des configurations et des fonctionnalités principales du SDK, accompagnée d’exemples de code.

## Table des Matières

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Fonctionnalités](#fonctionnalités)
    - [Envoyer une Transaction Push](#1-envoyer-une-transaction-push)
    - [Envoyer une Transaction Push avec Statut en Attente](#2-envoyer-une-transaction-push-avec-statut-en-attente)
    - [Vérifier le Statut d'une Transaction](#3-vérifier-le-statut-dune-transaction)
    - [Transfert Flooz vers un Compte Autorisé](#4-transfert-flooz-vers-un-compte-autorisé)
    - [Vérification du Solde d'un Abonné](#5-vérification-du-solde-dun-abonné)
    - Récupèrer le statut du compte mobile d’un abonné
    - Effectuer des transactions de dépôt d'argent (cash-in) vers un abonné
    - Effectuer des transactions de rechargement de crédit téléphonique pour un abonné
4. [Gestion des réponses](#gestion-des-réponses)
5. [Gestion des erreurs](#gestion-des-erreurs)
5. [Exceptions](#exceptions)
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
    telephone: '22995181010',
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

### 4. Transfert Flooz vers un Compte Autorisé

La méthode transfertFlooz permet de transférer des fonds depuis le compte du marchand vers un compte de destination autorisé par les configurations.

```php
<?php
$response = $moovApi->transfertFlooz(
    destination: '22995181010',
    amount: 10000,
    referenceId: 'Ref_12345',
    walletId: '0', // ID du portefeuille, par défaut "0"
    data: 'Transfert vers partenaire' // facultatif
);
```

#### Paramètres :

- `destination` : Numéro de téléphone de destination pour le transfert (string).
- `amount` : Montant du transfert (int).
- `referenceId` : Identifiant unique de la transaction pour suivi (string).
- `walletId` : ID du portefeuille utilisé pour le transfert (string, par défaut "0").
- `data` : Données additionnelles pour la transaction (string, facultatif).

### 5. Vérification du Solde d'un Abonné

La méthode getBalance permet de vérifier le solde actuel d'un compte abonné, en interrogeant le portefeuille principal par défaut (ID du portefeuille : 0).

```php
$response = $moovApi->getBalance('22995181010');
```

#### Paramètres :

- subscriberTelephone : Numéro de téléphone de l'abonné dont on souhaite consulter le solde (string).


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

- `isSuccess()` :
Permet de vérifier si l'opération a été traitée avec succès. Elle renvoie `true` si le code de statut de la réponse correspond au statut de succès (`0`) et `false` sinon.

- `isInPendingState() :`
Permet de vérifier si la transaction est actuellement en attente de traitement. Elle renvoie `true` si le code de statut correspond au statut d'attente (`100`) et `false` sinon.

- `toArray()` :
Convertit la réponse en un tableau associatif, en renvoyant toutes les données contenues dans la réponse. Cela est particulièrement utile pour le débogage ou l'enregistrement des réponses de l'API.

- `get(string $key)` :
Cette méthode générique permet d'accéder directement à une valeur spécifique dans la réponse en utilisant sa clé. Par exemple, pour accéder au champ status : `$response->get('status')`

### Réponse pour Transfert Flooz (TransferFloozResponse)

Lorsque vous effectuez un transfert de fonds avec la méthode `transfertFlooz`, les réponses spécifiques à cette méthode sont encapsulées dans un objet `TransferFloozResponse` au sein de `MoovMoneyApiResponse`, accessible via la propriété `TransferFlooz`. Cet objet offre un accès simplifié aux informations du transfert, telles que l’identifiant de la transaction, le solde avant/après, le coût et le bonus appliqué.

#### Méthodes principales de TransferFloozResponse :

- `getMessage()` : Récupère le message de réponse de l'API, indiquant le statut de la transaction.
- `getTransactionID()` : Récupère l'identifiant unique de la transaction (REFID).
- `getSenderKeyCost()` : Récupère le coût en clés pour l'expéditeur.
- `getSenderBonus()` : Récupère le bonus reçu par l'expéditeur.transfertFlooz
- `getSenderBalanceBefore()` : Récupère le solde de l’expéditeur avant le transfert.
- `getSenderBalanceAfter()` : Récupère le solde de l’expéditeur après le transfert.
- `toArray()` : Convertit la réponse en un tableau associatif.

### Réponse pour la Vérification de Solde (GetBalanceResponse)

Lorsque vous effectuez une vérification de solde avec la méthode `getBalance`, les réponses spécifiques à cette méthode sont encapsulées dans un objet `GetBalanceResponse` au sein de `MoovMoneyApiResponse`, accessible via la propriété GetBalance.

#### Méthodes principales de GetBalanceResponse :

- `getMessage()` : Récupère le message de réponse de l'API, indiquant le statut de la demande de solde.
- `getBalance()` : Récupère le solde actuel de l'abonné.
- `toArray()` : Convertit la réponse en un tableau associatif.


## Gestion des erreurs

Les erreurs envoyées par l'API Moov Money sont levées sous forme d'exceptions, comportant le message d'erreur associé, via la classe `ServerErrorException::class`. ([Plus d'informations](#exceptions))

## Exceptions

Le SDK Moov Money gère les erreurs en lançant des exceptions spécifiques pour faciliter le débogage et la gestion des erreurs. Voici les principales exceptions que vous pourriez rencontrer :

- `ServerErrorException::class` : Cette exception est levée lorsque l'API Moov Money renvoie une erreur de serveur. Cela peut se produire si le serveur Moov est temporairement inaccessible, en cas de requêtes malformées ou si une erreur interne se produit côté serveur. Lorsque cette exception est levée, vérifiez les logs et le message d'erreur retourné pour comprendre l'origine du problème.

- `BadConfigurationException::class` : Cette exception est levée lorsque la configuration du SDK est incorrecte ou incomplète. Elle peut survenir si des informations essentielles comme l'URL de base, le nom d'utilisateur, le mot de passe, ou la clé de chiffrement sont manquantes ou incorrectes. Avant de lancer des requêtes, assurez-vous que les paramètres de configuration sont bien définis et conformes aux spécifications fournies par Moov Money.

Ces exceptions permettent aux développeurs de réagir de manière appropriée aux différents types d'erreurs rencontrées lors des interactions avec l'API, en facilitant la gestion des cas d'erreur et en améliorant la robustesse des applications qui utilisent ce SDK.


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

## License

Ce projet est sous licence MIT, une licence open-source permissive qui permet une utilisation, modification et distribution libres du code. Pour plus de détails sur les conditions et les droits accordés par cette licence, consultez le fichier [LICENSE](/LICENSE.md) inclus dans le projet.

## Développeurs

- [Fortunatus KIDJE - @v1p3r75](https://github.com/v1p3r75) (Développeur principal)
