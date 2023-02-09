<template>
    <div>App</div>
</template>

<script>
import gql from "graphql-tag";
export default {
    name: "App",
    data() {
        return {
            ceremonies: [],
        };
    },
    apollo: {
        ceremonies: {
            query: gql`
                query ReadCeremonies {
                    readCeremonies {
                        nodes {
                            id
                        }
                    }
                }
            `,
            update: (data) => {
                console.log(data);
            },
        },
    },
};
</script>
