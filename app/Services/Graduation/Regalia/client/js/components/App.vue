<template>
    <form v-if="ceremonies?.length">
        <fieldset>
            <legend>Graduation Regalia Requirements</legend>
            <pre>{{ formData }}</pre>
            <fieldset>
                <legend>Graduation Attendance</legend>
                <ul>
                    <li v-for="ceremony in ceremonies" :key="ceremony.id">
                        <input
                            type="checkbox"
                            v-model="ceremony.attending"
                            :id="`ceremony-${ceremony.id}`"
                        />
                        <label :for="`ceremony-${ceremony.id}`">{{
                            ceremony.title
                        }}</label>
                    </li>
                </ul>
                <p>
                    Please indicate if you expect to be attending graduation
                    ceremonies
                </p>
            </fieldset>
            <fieldset>
                <legend>Regalia Hire Requirements</legend>
                <p>
                    Please record your Graduation Regalia requirements below.
                    This information will then be used to determine your
                    attendance and your gown requirements each time you attend a
                    graduation.
                </p>
                <p>
                    We strongly encourage you to wear the regalia of your
                    highest qualification, however you are welcome to wear a
                    lower qualification if you wish to match your school and/or
                    students.
                </p>
                <p>
                    Please note we do not order regalia from overseas. If you
                    gained your qualification from an overseas institution you
                    will be issued with an Otago University equivalent.
                </p>
                <div>
                    <label for="gown-hire"> Is Gown Hire Required? </label>
                    <select name="gown-hire" id="gown-hire" v-model="gownHire">
                        <option :value="false">
                            No, I do not require a gown to be hired
                        </option>
                        <option :value="true">
                            Yes, I do require a gown to be hired
                        </option>
                    </select>
                </div>
                <div>
                    <label for="trencher-hire">
                        Is Trencher Hire Required?
                    </label>
                    <select
                        name="trencher-hire"
                        id="trencher-hire"
                        v-model="trencherHire"
                    >
                        <option :value="false">
                            No, I do not require a matching Trencher
                        </option>
                        <option :value="true">
                            Yes, I require a matching Trencher
                        </option>
                    </select>
                </div>
                <div>
                    <label for="comments"> Comments / Questions </label>
                    <textarea
                        id="comments"
                        name="comments"
                        v-model="comments"
                    />
                </div>
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
            gownHire: false,
            trencherHire: false,
            comments: "",
        };
    },
    computed: {
        formData() {
            return {
                ceremonies: this.ceremonies,
                gownHire: this.gownHire,
                trencherHire: this.trencherHire,
                comments: this.comments,
            };
        },
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
            update: (data) =>
                data.readCeremonies.nodes.map(({ id, title }) => {
                    return { id, title, attending: false };
                }),
        },
    },
};
</script>
