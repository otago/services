import { ApolloClient, concat, createHttpLink, InMemoryCache } from '@apollo/client/core';
import { createApolloProvider } from '@vue/apollo-option';
import { createApp } from 'vue';
import App from './components/App.vue';

document.addEventListener('DOMContentLoaded', async () => {
    const appElement = document.getElementById('app');
    const data = Object.assign({}, appElement.dataset);
    const cache = new InMemoryCache();
    const link = createHttpLink({
        uri: data.graphql,
    });
    const apolloClient = new ApolloClient({
        link: link,
        cache,
    });
    const apolloProvider = createApolloProvider({
        defaultClient: apolloClient,
    })
    const app = createApp(App);
    app
        .use(apolloProvider);
    app.mount(appElement);
});
