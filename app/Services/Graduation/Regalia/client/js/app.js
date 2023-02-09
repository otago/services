import { createApp } from 'vue';
import RegaliaForm from './components/RegaliaForm.vue';
import getToken from './components/getToken';

document.addEventListener('DOMContentLoaded', () => {
    const appElement = document.getElementById('app');
    const data = Object.assign({}, appElement.dataset);
    const xCsrfToken = getToken(data);
    console.log(xCsrfToken);

    const app = createApp(RegaliaForm);
    app.mount(appElement);
});
