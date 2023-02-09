<template>
    <div>RegaliaForm</div>
</template>

<script>
import gql from "graphql-tag";
export default {
    name: "RegaliaForm",
    data() {
        return {
            memberId: 0,
        };
    },
    apollo: {
        memberId: {
            query: gql`
                query readAuthenticatedMembers {
                    readAuthenticatedMembers {
                        id
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
