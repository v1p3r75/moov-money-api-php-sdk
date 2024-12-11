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
   - [Récupèrer le statut du compte mobile d’un abonné](#6-récupération-du-statut-mobile-et-des-informations-kyc-getmobilestatus)
   - [Effectuer des transactions de dépôt d'argent (cash-in) vers un abonné](#7-effectuer-des-transactions-de-dépôt-dargent-cash-in-vers-un-abonné)
   - [Effectuer des transactions de rechargement de crédit téléphonique pour un abonné](#8-effectuer-des-transactions-de-rechargement-de-crédit-téléphonique-pour-un-abonné)
4. [Gestion des réponses](#gestion-des-réponses)
5. [Gestion des erreurs](#gestion-des-erreurs)
6. [Exceptions](#exceptions)
7. [Les Todos](#les-tâches-à-réaliser-todos)
8. [Contribution](#contribution)
9. [Développeur](#développeurs)

## Installation

Installez le SDK via Composer en exécutant la commande suivante :

```bash
composer require v1p3r75/moov-money-api-sdk
```

## Configuration

Avant d’utiliser le SDK, configurez les identifiants de connexion, le timeout pour les requêtes, clé de chiffrements, l’environnement (sandbox ou production), etc.
Le SDK inclut une classe `MoovMoneyAPIConfig` pour simplifier cette configuration.

```php
<?php

use MoovMoney\MoovMoneyAPI;
use MoovMoney\MoovMoneyAPIConfig;

$config = new MoovMoneyAPIConfig();
$config->setUsername('your_username')
       ->setPassword('your_password')
       ->setRequestTimeout(30) // en secondes
       ->useSandbox(true); // Active le mode sandbox (désactivez pour production)

$moovApi = new MoovMoneyAPI($config);

```

### Détails des Options de Configuration

| Option           | Type     | Description                                                                                                                                                                                                                                          |
| ---------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `username`       | `string` | Nom d'utilisateur fourni par Moov Money.|
| `password`       | `string` | Mot de passe pour l'authentification de l'API Moov Money.|
| `baseUrl`        | `string` | URL de l'API Moov Money. Par défaut, l'URL du mode sandbox est utilisée. En mode production, l'URL de prod est automatiquement définie lors de l'appel à useSandbox(false).|
| `encryptionKey`  | `string` | Clé de chiffrement pour générer les tokens d'authentification (32 caractères pour AES-256). Le SDK utilise la clé fournie par Moov : `tlc12345tlc12345tlc12345tlc12345`. Vous ne devez pas modifier cette clé, sauf si Moov en fournit une nouvelle. |
| `requestTimeout` | `float`  | Durée maximale (en secondes) pour les requêtes HTTP (60 par défaut).|
| `useSandbox` | `bool`  | Définit l'environnement (`true` pour `sandbox`, `false` pour `production`). Par défaut, le mode sandbox est activé.|

#### Gestion des Environnements (Sandbox et Production)

Le SDK prend en charge deux environnements : `sandbox` (test) et `production`.

Par défaut, l'environnement est configuré en mode sandbox pour garantir que les appels initiaux n'affectent pas les transactions réelles.
Vous pouvez basculer entre les environnements en utilisant la méthode `useSandbox`.

##### Exemple :
```php
<?php

$config = new MoovMoneyAPIConfig();

// Activer le mode production
$config->useSandbox(false);

// Vérifier l'environnement actif
if ($config->isSandbox()) {
    echo "Environnement actif : Sandbox";
} else {
    echo "Environnement actif : Production";
}
```

Lorsque vous passez en mode production avec useSandbox(false), l'URL de base (`https://testapimarchand2.moov-africa.bj:2010/com.tlc.merchant.api/UssdPush?wsdl`) est automatiquement mise à jour vers l'URL de production (`https://apimarchand.moov-africa.bj/com.tlc.merchant.api/UssdPush?wsdl`).

##### NB : _Si vous constatez que les URL utilisées par le SDK ne correspondent pas à celles fournies par Moov Money, n'hésitez pas à utiliser la méthode `setBaseUrl` pour les définir directement_.

## Fonctionnalités

Le SDK propose quelques fonctionnalités :

- Envoyer une transaction push (`pushTransaction`).
- Envoyer une transaction push avec statut en attente (`pushWithPendingTransaction`).
- Vérifier le statut d'une transaction (`getTransactionStatus`).
- Transférer des fonds depuis le compte du marchand vers un autre compte autorisé (`transferFlooz`).
- Vérifier le solde actuel d’un compte abonné, principalement le solde principal (`getBalance`).
- Récupèrer le statut du compte mobile d’un abonné, y compris les informations KYC (Know Your Customer) (`getMobileStatus`).
- Effectuer des transactions de dépôt d'argent (cash-in) vers un abonné.
- Effectuer des transactions de rechargement de crédit téléphonique pour un abonné.

### 1. Envoyer une Transaction Push

La méthode `pushTransaction` envoie une demande de paiement au client via une transaction push.

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

La méthode `pushWithPendingTransaction` envoie une demande de transaction push qui reste en attente jusqu’à confirmation du client. Ce dernier peut confirmer la transaction après grâce à un code USSD.

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

La méthode `getTransactionStatus` permet de vérifier le statut d'une transaction existante en fournissant son identifiant de référence.

```php
<?php

$statusResponse = $moovApi->getTransactionStatus('72024103000000009');

```

#### Paramètres :

- `referenceId` : Identifiant de la transaction dont on souhaite vérifier le statut (`string`).

### 4. Transfert Flooz vers un Compte Autorisé

La méthode `transferFlooz` permet de transférer des fonds depuis le compte du marchand vers un compte de destination autorisé par les configurations.

```php
<?php
$response = $moovApi->transferFlooz(
    destination: '22995181010',
    amount: 10000,
    referenceId: 'Ref_12345',
    walletId: '0', // ID du portefeuille, par défaut "0"
    data: 'Transfert vers partenaire' // facultatif
);
```

#### Paramètres :

- `destination` : Numéro de téléphone de destination pour le transfert (`string`).
- `amount` : Montant du transfert (`int`).
- `referenceId` : Identifiant unique de la transaction pour suivi (`string`).
- `walletId` : ID du portefeuille utilisé pour le transfert (`string`, par défaut `"0"`).
- `data` : Données additionnelles pour la transaction (`string`, facultatif).

### 5. Vérification du Solde d'un Abonné

La méthode `getBalance` permet de vérifier le solde actuel d'un compte abonné, en interrogeant le portefeuille principal par défaut (ID du portefeuille : 0).

```php
$response = $moovApi->getBalance('22995181010');
```

#### Paramètres :

- `subscriberTelephone` : Numéro de téléphone de l'abonné dont on souhaite consulter le solde (`string`).

### 6. Récupération du Statut Mobile et des Informations KYC (getMobileStatus)

La méthode `getMobileStatus` permet d’obtenir le statut d'un abonné et des informations KYC (par exemple, type de compte, nom, date de naissance, etc.).

```php
<?php

$response = $moovApi->getMobileStatus('22995181010');

```
#### Paramètres :

- `subscriberTelephone` : Numéro de téléphone de l'abonné dont on souhaite récupérer les informations KYC (`string`).

### 7. Effectuer des transactions de dépôt d'argent (cash-in) vers un abonné

La méthode `cashIn` permet d'éffectuer les transactions de dépôt d'argent (cash-in) vers un abonné.

```php
<?php

$response = $moovApi->cashIn(
    "98239988",
    2000,
    "10000000",
    "other_data"
);

```
#### Paramètres :

- `destination` : Numéro de téléphone de destination pour le dépôt (string).
- `amount` : Montant du dépôt (int).
- `referenceId` : Identifiant unique de la transaction pour suivi (string).
- `data` : Données additionnelles pour la transaction (string, facultatif).

### 8. Effectuer des transactions de rechargement de crédit téléphonique pour un abonné

La méthode `airTime` permet d'éffectuer les transactions de rechargement (airtime) de crédit téléphonique pour un abonné.

```php
<?php

$response = $moovApi->airTime(
    "98239988",
    2000,
    "10000000",
    "other_data"
);

```
#### Paramètres :

- `destination` : Numéro de téléphone de destination pour le rechargement (`string`).
- `amount` : Montant du rechargement (`int`).
- `referenceId` : Identifiant unique de la transaction pour suivi (`string`).
- `data` : Données additionnelles pour la transaction (`string`, facultatif).

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

- `getMessage()` : Récupère la clé `message` de réponse de l'API (disponible sur certaines opérations comme transferFlooz, GetMobileStatus, ...)

- `toArray()` :
  Convertit la réponse en un tableau associatif, en renvoyant toutes les données contenues dans la réponse. Cela est particulièrement utile pour le débogage ou l'enregistrement des réponses de l'API.

- `get(string $key)` :
  Cette méthode générique permet d'accéder directement à une valeur spécifique dans la réponse en utilisant sa clé. Par exemple, pour accéder au champ status : `$response->get('status')`

### Réponse pour Transfert Flooz (TransferFloozResponse)

Lorsque vous effectuez un transfert de fonds avec la méthode `transferFlooz`, les réponses spécifiques à cette méthode sont encapsulées dans un objet `TransferFloozResponse` au sein de `MoovMoneyApiResponse`, accessible via la propriété `TransferFlooz`. Cet objet offre un accès simplifié aux informations du transfert, telles que l’identifiant de la transaction, le solde avant/après, le coût et le bonus appliqué.

#### Méthodes principales de TransferFloozResponse :

- `getTransactionID()` : Récupère l'identifiant unique de la transaction (REFID).
- `getSenderKeyCost()` : Récupère le coût en clés pour l'expéditeur.
- `getSenderBonus()` : Récupère le bonus reçu par l'expéditeur.
- `getSenderBalanceBefore()` : Récupère le solde de l’expéditeur avant le transfert.
- `getSenderBalanceAfter()` : Récupère le solde de l’expéditeur après le transfert.

### Réponse pour la Vérification de Solde (GetBalanceResponse)

Lorsque vous effectuez une vérification de solde avec la méthode `getBalance`, les réponses spécifiques à cette méthode sont encapsulées dans un objet `GetBalanceResponse` au sein de `MoovMoneyApiResponse`, accessible via la propriété `GetBalance`.

#### Méthodes principales de GetBalanceResponse :

- `getBalance()` : Récupère le solde actuel de l'abonné.

### Réponse pour la Récupération du Statut Mobile et des Informations KYC (getMobileStatus)

Lorsque vous effectuez une récupération de statut avec la méthode `getMobileStatus`, les réponses spécifiques à cette méthode sont encapsulées dans un objet `GetMobileStatusResponse` au sein de `MoovMoneyApiResponse`, accessible via la propriété `GetMobileStatus`.

#### Méthodes principales de GetMobileStatusResponse :

- `getAccountType()` : Retourne le type de compte de l'abonné.
- `getAllowedTransfer()` : Retourne le montant maximal autorisé pour les transferts de cet abonné.
- `getCity()` : Retourne la ville de résidence de l'abonné.
- `getDateOfBirth()` : Retourne la date de naissance de l'abonné au format Y-m-d H:i:s.
- `getFirstName()` : Retourne le prénom de l'abonné.
- `getLastName()` : Retourne le nom de famille de l'abonné.
- `getSecondName()` : Retourne le deuxième prénom de l'abonné, s'il est disponible.
- `getTelephone()` : Retourne le numéro de téléphone (MSISDN) de l'abonné.
- `getRegion()` : Retourne la région de résidence de l'abonné.
- `getStreet()` : Retourne l'adresse postale de l'abonné.
- `getSubscriberStatus()` : Retourne le statut actuel de l'abonné (par exemple, ACTIVE ou INACTIVE).

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
- [x] GetMobileStatus : récupèrer le statut du compte mobile d’un abonné, y compris les informations KYC (Know Your Customer).
- [x] Cash In Transactions : effectuer des transactions de dépôt d'argent (cash-in) vers un abonné.
- [x] Airtime Transactions : effectuer des transactions de rechargement de crédit téléphonique pour un abonné.

- [ ] Supplémentaires (+) :

    - [ ] Retourner les erreurs envoyées par le serveur de MoovMoney si possible dans la classe `MoovMoneyApiResponse` au lieu de lever une exception qui comporte l'erreur. 

## Contribution

Les contributions sont les bienvenues ! Pour signaler un bug ou proposer des fonctionnalités, veuillez soumettre une issue ou une pull request. [Plus sur comment contributer](./CONTRIBUTING.md).

## License

Ce projet est sous licence MIT, une licence open-source permissive qui permet une utilisation, modification et distribution libres du code. Pour plus de détails sur les conditions et les droits accordés par cette licence, consultez le fichier [LICENSE](/LICENSE.md) inclus dans le projet.

## Développeurs

- [Fortunatus KIDJE - @v1p3r75](https://github.com/v1p3r75) (Développeur principal)
