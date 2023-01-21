module.exports = {
    content: [
        './resources/**/*.antlers.html',
        './resources/**/*.blade.php',
        './resources/**/*.vue',
        './content/**/*.md'
    ],
    theme: {
        extend: {
            boxShadow: {
                'neon': '0 0 .2rem #fff, 0 0 .2rem #fff, 0 0 2rem #6bc4ff, 0 0 0.8rem #6bc4ff, 0 0 2.8rem #6bc4ff, inset 0 0 1.3rem #6bc4ff',
                '3xl': '0 25px 28px -26px rgba(0,0,0,0.50), 0 16px 50px 1px rgba(0,0,0,0.11)',
            },
            borderRadius: {
                'blob': '30% 70% 70% 30% / 30% 30% 70% 70%',
            },
            colors: {
                green: {
                    50: '#eefff5',
                    100: '#d7ffe9',
                    200: '#b2ffd4',
                    300: '#6bffae', // background
                    400: '#33f58c',
                    500: '#09de6a',
                    600: '#01b855',
                    700: '#059046',
                    800: '#0a713a',
                    900: '#0a5d33',
                },
                cyan: {
                    50: '#f0f8ff',
                    100: '#dff0ff',
                    200: '#b8e1ff',
                    300: '#6bc4ff', // background
                    400: '#33b0fd',
                    500: '#0996ee',
                    600: '#0076cc',
                    700: '#005da5',
                    800: '#045088',
                    900: '#0a4370',
                },
                purple: {
                    50: '#f6f2ff',
                    100: '#ede8ff',
                    200: '#ddd4ff',
                    300: '#c6b1ff',
                    400: '#aa85ff',
                    500: '#9f6bff', // background
                    600: '#8230f7',
                    700: '#741ee3',
                    800: '#6118bf',
                    900: '#50169c',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}
