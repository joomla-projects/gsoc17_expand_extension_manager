const Fs = require('fs');
const Path = require('path');
const Recurs = require('recursive-readdir');
const UglyCss = require('uglifycss');
const MakeDir = require('./utils/make-dir.es6.js');
const CompileScss = require('./stylesheets/scss-transform.es6.js');
const RootPath = require('./utils/rootpath.es6.js')._();

/**
 * Method that will crawl the media_source folder and
 * compile any scss files to css and .min.css
 * copy any css files to the appropriate destination and
 * minify them in place
 *
 * Expects scss files to have ext: .scss
 *         css files to have ext: .css
 * Ignores scss files that their filename starts with `_`
 *
 * @param {object} options  The options
 * @param {string} path     The folder that needs to be compiled, optional
 */
module.exports.compile = (options, path) => {
  Promise.resolve()
  // Compile the scss files
    .then(() => {
      let files = [];
      let folders = [];

      if (path) {
        const stats = Fs.lstatSync(`${RootPath}/${path}`);

        if (stats.isDirectory()) {
          folders.push(`${RootPath}/${path}`);
        } else if (stats.isFile()) {
          files.push(`${RootPath}/${path}`);
        } else {
          // eslint-disable-next-line no-console
          console.error(`Unknown path ${path}`);
          process.exit(1);
        }
      } else {
        files = [
          `${RootPath}/templates/cassiopeia/scss/offline.scss`,
          `${RootPath}/templates/cassiopeia/scss/template.scss`,
          `${RootPath}/templates/cassiopeia/scss/template-rtl.scss`,
          `${RootPath}/administrator/templates/atum/scss/bootstrap.scss`,
          `${RootPath}/administrator/templates/atum/scss/font-awesome.scss`,
          `${RootPath}/administrator/templates/atum/scss/template.scss`,
          `${RootPath}/administrator/templates/atum/scss/template-rtl.scss`,
          `${RootPath}/installation/template/scss/template.scss`,
          `${RootPath}/installation/template/scss/template-rtl.scss`,
        ];

        folders = [
          `${RootPath}/build/media_source`,
        ];
      }

      // Loop to get the files that should be compiled via parameter
      folders.forEach((folder) => {
        Recurs(folder, ['*.js', '*.map', '*.svg', '*.png', '*.swf', '*.json']).then(
          (filesRc) => {
            filesRc.forEach(
              (file) => {
                if (file.match(/\.scss/) && file.charAt(0) !== '_') {
                  files.push(file);
                }
                if (file.match(/\.css/)) {
                  // CSS file, we will copy the file and then minify it in place
                  // Ensure that the directories exist or create them
                  MakeDir.run(Path.dirname(file).replace('/build/media_source/', '/media/').replace('\\build\\media_source\\', '\\media\\'));
                  Fs.copyFileSync(file, file.replace('/build/media_source/', '/media/').replace('\\build\\media_source\\', '\\media\\'));
                  Fs.writeFileSync(
                    file.replace('/build/media_source/', '/media/').replace('\\build\\media_source\\', '\\media\\').replace('.css', '.min.css'),
                    UglyCss.processFiles([file], { expandVars: false }),
                    { encoding: 'utf8' },
                  );

                  // eslint-disable-next-line no-console
                  console.log(`CSS file copied/minified: ${file}`);
                }
              },
              (error) => {
                // eslint-disable-next-line no-console
                console.error(`something exploded ${error}`);
              },
            );
          },
        );

        files.forEach((inputFile) => {
          CompileScss.compile(inputFile, options);
        });
      });
    })

    // Handle errors
    .catch((error) => {
      // eslint-disable-next-line no-console
      console.error(`${error}`);
      process.exit(1);
    });
};
