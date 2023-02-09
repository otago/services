import { ApolloClient, ApolloLink, concat, createHttpLink, InMemoryCache } from '@apollo/client/core';
import { createApolloProvider } from '@vue/apollo-option';
import { createApp } from 'vue';
import RegaliaForm from './components/RegaliaForm.vue';
import getToken from './components/getToken';

document.addEventListener('DOMContentLoaded', () => {
    const appElement = document.getElementById('app');
    const data = Object.assign({}, appElement.dataset);
    const xCsrfToken = getToken(data);
    if (!xCsrfToken) {
        return;
    }
    const cache = new InMemoryCache();
    const link = createHttpLink({
        uri: data.graphqlUrl,
    });
    const authMiddleware = new ApolloLink((operation, forward) => {
        operation.setContext({
            headers: {
                "X-CSRF-TOKEN": data.xCsrfToken
            }
        });
        return forward(operation);
    })
    const apolloClient = new ApolloClient({
        link: concat(authMiddleware, link),
        cache,
    });
    const apolloProvider = createApolloProvider({
        defaultClient: apolloClient,
    })
    const app = createApp(RegaliaForm);
    app.use(apolloProvider);
    app.mount(appElement);
});
