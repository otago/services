import { ApolloClient, concat, createHttpLink, InMemoryCache } from '@apollo/client/core';
import { createApolloProvider } from '@vue/apollo-option';
import { createApp } from 'vue';
import store from './store';
import RegaliaForm from './components/RegaliaForm.vue';
// import getToken from './components/getToken';

document.addEventListener('DOMContentLoaded', () => {
    const appElement = document.getElementById('app');
    const data = Object.assign({}, appElement.dataset);
    // const jwtToken = getToken(data);
    // console.log(jwtToken);
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
    const app = createApp(RegaliaForm);
    app
        .use(store)
        .use(apolloProvider);
    app.mount(appElement);
});
