# WP Temps Lecture

**Plugin WordPress pour estimer et afficher le temps de lecture d'un article.**

WP Temps Lecture est une extension légère qui calcule automatiquement le temps de lecture estimé en fonction du contenu d'un article, et l'affiche au début du contenu ou à un endroit personnalisé via un shortcode.

---

## Fonctionnalités

- Estime le temps de lecture d'un article en se basant sur le nombre de mots.
- Affichage automatique en haut de l'article.
- Utilisation possible d'un shortcode `[reading_time]` pour afficher le temps où vous voulez.
- Personnalisation facile du texte affiché.

---

## Installation

1. Téléchargez le plugin depuis GitHub.
2. Décompressez l'archive et envoyez le dossier `wp-temps-lecture` dans votre répertoire `/wp-content/plugins/`.
3. Activez le plugin via l'interface d'administration de WordPress.

---

## Utilisation

- Par défaut, le temps de lecture est affiché automatiquement au début de chaque article.
- Vous pouvez insérer manuellement le temps de lecture en utilisant le shortcode suivant : [reading_time]
- Le calcul se base sur une vitesse moyenne de lecture de **200 mots par minute**.

---

## Personnalisation

Pour personnaliser la vitesse de lecture ou le texte affiché, vous pouvez modifier les filtres WordPress suivants dans votre thème ou un plugin :

```php
// Modifier la vitesse de lecture (mots par minute)
add_filter('wp_temps_lecture_wpm', function($wpm) {
  return 250; // Par exemple, 250 mots/minute
});

// Modifier le format d'affichage
add_filter('wp_temps_lecture_text', function($text, $minutes) {
  return 'Temps estimé : ' . $minutes . ' minute(s)';
}, 10, 2);
```

## À propos

Développé par Alban de Crea-Troyes.
N'hésitez pas à laisser vos retours, suggestions ou améliorations !

