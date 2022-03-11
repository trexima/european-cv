# European CV bundle
Bundle provides form for creating CV in european format. It also optionally embeds required JS and predefined CSS.
CV can be saved to database, exported to DOC or PDF format.

## Installation
Add to composer.json:
```console
composer require trexima/european-cv
```


JS and CSS libs:
```
yarn add bootstrap
yarn add @fortawesome/fontawesome-free
yarn add jquery
yarn add jquery-ui
yarn add blueimp-file-upload
yarn add sortablejs
yarn add jquery.dirtyforms
yarn add select2
yarn add select2-bootstrap-theme
yarn add parsleyjs
yarn add flatpickr
yarn add bs-custom-file-input
```

NOTE: For required external libs include:
```
./vendor/trexima/european-cv-bundle/Resources/assets/css/shared.scss
```

### Installing assets with Webpack encore:
```js
Encore.addEntry('trexima-european-cv', [
    './vendor/trexima/european-cv/Resources/assets/js/main.js',
    './vendor/trexima/european-cv/Resources/public/build/trexima-european-cv.css'
])
```

**Fucking jquery.dirtyforms hack(webpack.config.js):**
```js
const config = Encore.getWebpackConfig();
// Required because of bug in jquery.dirtyforms https://github.com/snikch/jquery.dirtyforms/issues/82
config.externals = {
    window: 'window',
    document: 'document'
};

// export the final configuration
module.exports = config;
```

Add CSS and JS to Twig templates:
```twig
{% block stylesheets %}
    {{ encore_entry_link_tags('trexima-european-cv') }}
    {{ parent() }}
{% endblock %}

{% block javascripts %}
    {{ encore_entry_script_tags('trexima-european-cv') }}
    {{ parent() }}
{% endblock %}
```

## Configuration
Add upload route to global configuration:
```
trexima_european_cv_annotations:
    resource: '@TreximaEuropeanCvBundle/Controller/'
    type:     annotation
```

Route name for AJAX images uploading:
```
trexima_european_cv_bundle_image
```

Define entity that will be in relation with european CV and upload dir:
```
trexima_european_cv:
  upload_url: '/uploads/european-cv/'
  upload_dir: '%kernel.project_dir%/public/uploads/european-cv/'
  user_class: App\Entity\User
```
**trexima_european_cv.yaml**

**NOTE: Don't forget to update Doctrine schema.**

### Translations
If you wish to use default texts provided in this bundle, you have to make sure you have translator enabled in your config.
```yaml
framework:
    translator: ~
```
