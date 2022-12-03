# Project Requirements

* PHP 7.4 ~ 8.0
* gettext (for `npm run gettext:extract` and `npm run gettext:compile`)
    * Ubuntu:
        `sudo apt-get update`
        `sudo apt-get install gettext`
    * MacOS:
        `brew install gettext`
        `brew link --force gettext`
    * Windows:
        URL: https://mlocati.github.io/articles/gettext-iconv-windows.html
        Binary: 64-bit static recommended

# Dev Prerequisites

Knowledge of the following is required in order to competently build, maintain, add to, or debug
this project:

* PHP 8 w/ Tell-PHP framework
* MySQL, MariaDB
* Node.js
* Vue.js 3 w/ Pinia
* Tailwind 3
* Webpack
* Git
* Bitbucket Pipelines

You should have proper tooling configured for your editor/IDE. Ideally, your editor should respect
and be configured to automatically format documents based on the workspace's root config files,
including:

* .editorconfig
* .eslintrc
* .php-cs-fixer

If your IDE lacks the extensions or plugins needed to apply the aforementioned configurations
automatically on save, consider configuring a local script yourself to help ensure this is done
on commit. If the lead engineer sees nonsense like tab indentation that's not been converted to
spaces, inconsistent new lines, or a distinct and purposeful variation in the code style or naming
conventions already established and easily visible within the project, you'll be kicked off the
project entirely.

# Dev Workflow

* If you're a front-end developer, most of your work will be done in the `src/app/dom/*/vue/*` folders.
    * You should have `npm run webpack` running during every editor/IDE session.
    * Be sure to run `npm run webpackall` before committing changes to the repo (just for safe measure).

> As a company, we're still learning Vue and will likely switch to something like Nuxt/Vue for future
projects (using the Tell-PHP framework for backend only). But this specific project is a hybrid.

# Dev Installation

1. Run `composer install` in `src/`.
    * This queries private Bitbucket repos.
    * We'll grant you READ access to these repos upon request (we'll need your BB username/email).
    * If you have trouble getting the code after getting access to repos (sometimes Composer acts weird behind private repos), let us know and we'll send you an `.zip` or `.tar` archive of the `vendor/*` directory (or you can grab it from the staging environment's FTP).
2. Create a local MySQL database.
3. Create a `.env` in `src/*` (use `.env.example` as a template).
4. In your browser, navigate to https://your-local-url/__graft/run to run the applicable database grafts.
5. Navigate to https://your-local-url/admin
6. Login using credentials you'll find in line 30 (ish) of `src/app/grafts/*_install.php`

# Pipelines

* For now we have a single staging environment setup under the `development` branch.
* You can see the pipeline details in `bitbucket-pipelines.yml`.
    * The development environment has the public root directory called `content` which can't be changed 😦, hence the reason we upload the `src/public/` in a separate stage.
