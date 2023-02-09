<template>
    <form v-if="ceremonies?.length">
        <fieldset>
            <legend>Graduation Regalia Requirements</legend>
            <fieldset>
                <legend>Graduation Attendance</legend>
                <ul>
                    <li v-for="{ id, title } in ceremonies" :key="id">
                        <input
                            type="checkbox"
                            name="attendance"
                            :value="id"
                            :id="`ceremony-${id}`"
                        />
                        <label :for="`ceremony-${id}`">{{ title }}</label>
                    </li>
                </ul>
                <p>
                    Please indicate if you expect to be attending graduation
                    ceremonies
                </p>
            </fieldset>
        </fieldset>
    </form>
    <div v-else>Loading...</div>
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
                            title
                        }
                    }
                }
            `,
            update: (data) => data.readCeremonies.nodes,
        },
    },
};
</script>
