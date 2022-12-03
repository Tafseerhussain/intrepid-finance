// https://jshmrtn.github.io/vue3-gettext/configuration.html
module.exports = {
    input: {
        path    : './src/app/dom',                    // only files in this directory are considered for extraction
        include : ['**/*.js', '**/*.ts', '**/*.vue'], // glob patterns to select files for extraction
        exclude : [],                                 // glob patterns to exclude files from extraction
    },
    output: {
        path      : './src/app/lang',      // output path of all created files
        potPath   : './messages.pot',      // relative to output.path, so by default './src/language/messages.pot'
        jsonPath  : './translations.json', // relative to output.path, so by default './src/language/translations.json'
        locales   : ['en'],                // supported locals
        flat      : false,                 // don't create subdirectories for locales
        linguas   : true,                  // create a LINGUAS file
        splitJson : false,                 // create separate json files for each locale. If used, jsonPath must end with a directory, not a file
    },
};
