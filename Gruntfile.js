module.exports = function(grunt) {


    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        // secrets.json is ignored in git because it contains sensitive data
        // See the README for configuration settings
        secrets: grunt.file.readJSON('secrets.json'),


        // Re-usable filesystem paths (these shouldn't be modified)
        paths: {
          src:        'src',
          src_img:    'src/img',
          dist:       'dist',
          dist_img:   'dist/img'
        },

        // Takes your scss files and compiles them to css
        sass: {
          dist: {
            options: {
              style: 'expanded'
            },
            files: {
              '<%= paths.dist %>/css/main.css': '<%= paths.src %>/sass/main.scss',
            }
          }
        },

        // Assembles your email content with html layout, does only a specific one if specified
        assemble: {
          options: {
            layoutdir: '<%= paths.src %>/layouts',
            partials: ['<%= paths.src %>/partials/*.hbs'],
            helpers: ['<%= paths.src %>/helpers/*.js'],
            data: ['<%= paths.src %>/data/*.{json,yml}'],
            flatten: true
          },
          pages: {
            src: grunt.option('page')?['<%= paths.src %>/pages/**/'+ grunt.option('page') +'.hbs']:['<%= paths.src %>/pages/**/*.hbs'],
            dest: '<%= paths.dist %>/'
          },
        },

        // Optimize images
        imagemin: {
          dynamic: {
            options: {
              optimizationLevel: 4,
              svgoPlugins: [{ removeViewBox: false }]
            },
            files: [{
              expand: true,
              cwd: '<%= paths.src_img %>',
              src: ['**/*.{png,jpg,gif}'],
              dest: '<%= paths.dist_img %>'
            }]
          }
        },

        // Watches for changes to css or email templates then runs grunt tasks
        watch: {
          all: {
            files: ['<%= paths.src %>/**/*.scss','<%= paths.src %>/pages/**/*','<%= paths.src %>/layouts/*','<%= paths.src %>/partials/*','<%= paths.src %>/data/*'],
            tasks: ['default']
          },
          specific: {
            files: ['<%= paths.src %>/pages/*/' + grunt.option('page') + '.hbs'],
            tasks: ['watchSpecific']
          }
        },

        // Replace compiled template images for different situations, as well as remove ampersand html character with &
        replace: {
          ampersand: { // replacing all &amp; with & for GA parameters - use with caution
            options: {
              usePrefix: false,
              patterns: [
                {
                  match: '&amp;',
                  replacement: '&'
                }
              ]
            },
            files: [{
              expand: true,
              flatten: true,
              src: grunt.option('page')?['<%= paths.dist %>/'+ grunt.option('page')+'.html']:['<%= paths.src %>/pages/**/*.hbs'],
              dest: '<%= paths.dist %>'
            }]
          }
        },

        // used to clean out the image files and put them in the img_archive directory when needed
        shell: {
          archive_img: {
            options: {
              failOnError: false,
              stderr: false,
            },
            command:'mv ./src/img/* ./src/img_archive/ && rm -rf ./dist/img/*'
          }
        },


        // Use Mailgun option if you want to email the design to your inbox or to something like Litmus
        // mailgun: {
        //   mailer: {
        //     options: {
        //       key: '<%= secrets.mailgun.api_key %>', // See README for secrets.json
        //       sender: '<%= secrets.mailgun.sender %>', // See README for secrets.json
        //       subject: grunt.option('subject') + ' test #' + Math.floor(Math.random() * (1000 - 1 + 1) + 1),
        //       recipient: grunt.option('to') || '<%= secrets.mailgun.recipient.default %>', // See README for secrets.json or
        //     },
        //     src: ['<%= paths.dist %>/'+grunt.option('page')+'.html']
        //   },
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-assemble');
    grunt.loadNpmTasks('grunt-mailgun');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-replace');
    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-litmus');

    // Where we tell Grunt what to do when we type "grunt" into the terminal or grunt watch -- needed sass changes
    grunt.registerTask('default', ['sass','assemble','imagemin']);
    grunt.registerTask('watchSpecific', ['sass','assemble','imagemin']);

    // grunt clean
    grunt.registerTask('clean', ['shell:archive_img']);

};
