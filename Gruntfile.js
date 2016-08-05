module.exports = function (grunt) {

    var files = grunt.file.readJSON('files.json');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            options: {
                mangle: false,
                sourceMap: false
            },
            compiling: {
                files: {
                    'dist/form.controls.min.js': files
                }
            }
        },

        concat: {
            options: {
                separator: "\n"
            },
            compiling: {
                files: {
                    'dist/form.controls.js': files
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['uglify', 'concat']);

};
