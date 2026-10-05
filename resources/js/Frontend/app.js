import './bootstrap';

import { createApp, h } from 'vue';
import {createHead} from '@unhead/vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { ZiggyVue } from 'ziggy';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { VueRecaptchaPlugin } from 'vue-recaptcha'


const appName = window.document.getElementsByTagName('title')[0]?.innerText || '';
const head = createHead();
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({
            render: () => h(app, props),
        })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(head)
            .use(VueRecaptchaPlugin, {
                v2SiteKey:props.initialPage.props.recaptcha_site_key,
                v3SiteKey: 'YOUR_V3_SITEKEY_HERE',
            })
            // .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
