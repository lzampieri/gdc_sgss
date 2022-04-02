module.exports = {
    mode: 'jit',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme:
        require( './resources/js/theme.js' )
    ,
    plugins: [
        require('@tailwindcss/forms')
    ],
}
