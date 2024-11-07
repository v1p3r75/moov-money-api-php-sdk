# Contribuer au SDK PHP Moov Money

Merci de votre intérêt pour contribuer au SDK PHP Moov Money ! Votre soutien est précieux pour améliorer ce projet. Voici quelques consignes pour vous guider dans votre contribution.

> Et si vous aimez le projet, mais que vous n'avez pas le temps d'y contribuer, ce n'est pas grave. Il existe d'autres moyens simples de soutenir le projet et de montrer votre appréciation, ce qui nous ferait également très plaisir :
> - Ajoutez une étoile au projet
> - Tweetez à ce sujet
> - Référencez ce projet dans le fichier readme de votre projet
> - Mentionnez le projet lors de rencontres locales et parlez-en à vos amis/collègues

---

## Démarrage

1. **Forkez le dépôt** et clonez-le sur votre machine locale :

```bash
git clone https://github.com/v1p3r75/moov-money-api-php-sdk.git
cd moov-money-api-php-sdk
```

## Installez les dépendances :

Assurez-vous d'avoir Composer installé.
Installez les dépendances du package :
```bash
composer install
```

## Outils de Développement

Le SDK utilise les bibliothèques suivantes :

### [PestPHP (pestphp/pest)]('https://pestphp.com/')

Utilisé pour les tests. Pest fournit une expérience de test simplifiée.

Exécutez tous les tests avec :

```bash
vendor/bin/pest
```

### [PHPStan (phpstan/phpstan)](https://phpstan.org/)

Utilisé pour l'analyse statique du code afin de détecter les erreurs potentielles.

Exécutez PHPStan avec :

```bash
vendor/bin/phpstan analyse src
```

### [PHP-CS-Fixer (friendsofphp/php-cs-fixer)](https://cs.symfony.com/)

Utilisé pour la mise en forme du code afin de maintenir une cohérence de style.

Exécutez PHP-CS-Fixer pour formater le code :

```bash
vendor/bin/php-cs-fixer fix
```


## Normes de Code

Nous suivons les normes de codage [`PSR-12`]("https://www.php-fig.org/psr/psr-12/") pour PHP. Veuillez vous assurer que votre code respecte ces normes en exécutant `vendor/bin/php-cs-fixer fix` avant de soumettre une pull request.

## Écrire et Exécuter des Tests

### Écrire des Tests :

- Toutes les nouvelles fonctionnalités et corrections de bugs doivent être accompagnées de tests correspondants.
- Les fichiers de tests sont situés dans le répertoire tests.
- Utilisez Pest pour écrire et exécuter les tests.

### Exécuter les Tests :

Exécutez tous les tests pour vérifier que tout fonctionne correctement.
```bash
vendor/bin/pest
```

### Couverture des Tests :

Visez une couverture de code élevée. Ajoutez des tests pour couvrir les cas limites et les comportements attendus.

## Faire une Contribution

### Créez une nouvelle branche pour votre fonctionnalité ou correction de bug :

```bash
git checkout -b feature/nom-de-votre-fonctionnalite
```
### Validez vos changements :

- Faites des commits descriptifs et concis pour suivre vos modifications.

- Assurez-vous que tous les tests réussissent avant de valider.

### Exécutez l'analyse statique et mettez en forme le code :

- Exécutez PHPStan et PHP-CS-Fixer pour garantir que la qualité du code respecte nos standards.

### Poussez votre branche vers GitHub et ouvrez une Pull Request :

```bash
git push origin feature/nom-de-votre-fonctionnalite
```