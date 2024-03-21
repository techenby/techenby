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
                'blob-1': '30% 70% 70% 30% / 30% 30% 70% 70%',
                'blob-2': '58% 42% 75% 25% / 76% 46% 54% 24%',
                'blob-3': '50% 50% 33% 67% / 55% 27% 73% 45%',
                'blob-4': '33% 67% 58% 42% / 63% 68% 32% 37%',
            },
            typography: ({ theme }) => ({
                purple: {
                  css: {
                    '--tw-prose-body': theme('colors.slate[800]'),
                    '--tw-prose-headings': theme('colors.slate[900]'),
                    '--tw-prose-lead': theme('colors.purple[700]'),
                    '--tw-prose-links': theme('colors.purple[900]'),
                    '--tw-prose-bold': theme('colors.slate[900]'),
                    '--tw-prose-counters': theme('colors.purple[600]'),
                    '--tw-prose-bullets': theme('colors.purple[400]'),
                    '--tw-prose-hr': theme('colors.purple[300]'),
                    '--tw-prose-quotes': theme('colors.purple[900]'),
                    '--tw-prose-quote-borders': theme('colors.purple[300]'),
                    '--tw-prose-captions': theme('colors.purple[700]'),
                    '--tw-prose-code': theme('colors.purple[900]'),
                    '--tw-prose-pre-code': theme('colors.purple[100]'),
                    '--tw-prose-pre-bg': theme('colors.purple[900]'),
                    '--tw-prose-th-borders': theme('colors.purple[300]'),
                    '--tw-prose-td-borders': theme('colors.purple[200]'),
                    '--tw-prose-invert-body': theme('colors.slate[200]'),
                    '--tw-prose-invert-headings': theme('colors.white'),
                    '--tw-prose-invert-lead': theme('colors.purple[300]'),
                    '--tw-prose-invert-links': theme('colors.purple[300]'),
                    '--tw-prose-invert-bold': theme('colors.white'),
                    '--tw-prose-invert-counters': theme('colors.purple[400]'),
                    '--tw-prose-invert-bullets': theme('colors.purple[600]'),
                    '--tw-prose-invert-hr': theme('colors.purple[700]'),
                    '--tw-prose-invert-quotes': theme('colors.purple[100]'),
                    '--tw-prose-invert-quote-borders': theme('colors.purple[700]'),
                    '--tw-prose-invert-captions': theme('colors.purple[400]'),
                    '--tw-prose-invert-code': theme('colors.white'),
                    '--tw-prose-invert-pre-code': theme('colors.purple[300]'),
                    '--tw-prose-invert-pre-bg': 'rgb(0 0 0 / 50%)',
                    '--tw-prose-invert-th-borders': theme('colors.purple[600]'),
                    '--tw-prose-invert-td-borders': theme('colors.purple[700]'),
                  },
                },
              }),
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}
