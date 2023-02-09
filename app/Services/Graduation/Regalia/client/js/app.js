import { ApolloClient, ApolloLink, concat, createHttpLink, InMemoryCache } from '@apollo/client/core';
import { createApolloProvider } from '@vue/apollo-option';
import { createApp } from 'vue';
import App from './components/App.vue';
import getJwt from './components/getJwt';
import parseJwt from './components/parseJwt';

document.addEventListener('DOMContentLoaded', async () => {
    const appElement = document.getElementById('app');
    const data = Object.assign({}, appElement.dataset);
    const cache = new InMemoryCache();
    const jwt = getJwt(data.authenticate);
    if (!jwt) {
        return;
    }
    const link = createHttpLink({
        uri: data.graphql,
    });
    const authMiddleware = new ApolloLink((operation, forward) => {
        operation.setContext({
            headers: {
                "Authorization": jwt
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
    const app = createApp(App, { memberId: parseJwt(jwt).memberId, jwt: jwt });
    app.use(apolloProvider);
    app.mount(appElement);
});
