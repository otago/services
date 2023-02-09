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
                    <label for="gownHire"> Is Gown Hire Required? </label>
                    <select name="gownHire" id="gownHire" v-model="gownHire">
                        <option :value="false">
                            No, I do not require a gown to be hired
                        </option>
                        <option :value="true">
                            Yes, I do require a gown to be hired
                        </option>
                    </select>
                    <fieldset v-if="gownHire">
                        <legend>
                            Gowns can be collected from B Block during Gown Room
                            open hours.
                        </legend>
                        <div>
                            <label for="gownLength"> Gown Length </label>
                            <select
                                name="gownLength"
                                id="gownLength"
                                v-model="gownLength"
                            >
                                <option value="S">
                                    S = Short, between 150cm and 163cm
                                </option>
                                <option value="SP">
                                    SP = Short &amp; Petite, 158cm or shorter
                                </option>
                                <option value="M">
                                    M = Medium, between 164cm and 176cm
                                </option>
                                <option value="T">
                                    T = Tall, between 177cm and 187cm
                                </option>
                                <option value="XT">
                                    XT = Extra Tall, 188cm or taller
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="preferredGown">
                                Preferred Gown (Please select your highest
                                qualification)
                            </label>
                            <select
                                name="preferredGown"
                                id="preferredGown"
                                v-model="preferredGown"
                            >
                                <option value="">Other</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div>
                    <label for="trencherHire">
                        Is Trencher Hire Required?
                    </label>
                    <select
                        name="trencherHire"
                        id="trencherHire"
                        v-model="trencherHire"
                    >
                        <option :value="false">
                            No, I do not require a matching Trencher
                        </option>
                        <option :value="true">
                            Yes, I require a matching Trencher
                        </option>
                    </select>
                    <fieldset v-if="trencherHire">
                        <legend>Trencher Details</legend>
                        <label for="trencherSize"> Trencher Size </label>
                        <select
                            name="trencherSize"
                            id="trencherSize"
                            v-model="trencherSize"
                        >
                            <option
                                v-for="index in trencherRange"
                                :key="index"
                                :value="index + trencherMinSize - 1"
                            >
                                {{ index + trencherMinSize - 1 }}cm
                            </option>
                        </select>
                    </fieldset>
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
            gownLength: "M",
            preferredGown: "",
            trencherSize: 52,
            trencherMinSize: 52,
            trencherMaxSize: 65,
        };
    },
    computed: {
        trencherRange() {
            return this.trencherMaxSize - this.trencherMinSize + 1;
        },
        formData() {
            return {
                ceremonies: this.ceremonies,
                gownHire: this.gownHire,
                gownLength: this.gownHire ? this.gownLength : null,
                preferredGown: this.gownHire ? this.preferredGown : null,
                trencherHire: this.trencherHire,
                trencherSize: this.trencherHire ? this.trencherSize : null,
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
