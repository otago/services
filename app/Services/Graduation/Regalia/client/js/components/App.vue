<template>
    <div>Token: {{ token }}</div>
</template>

<script>
import gql from "graphql-tag";
export default {
    name: "App",
    data() {
        return {
            token: "",
        };
    },
    apollo: {
        token: {
            query: gql`
                query readJWTs {
                    readJWTs {
                        token
                    }
                }
            `,
            update: (data) => (data.readJWTs.length ? data.readJWTs[0] : ""),
        },
    },
};
</script>
