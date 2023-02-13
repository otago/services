<template>
    <div v-if="error">An error has occurred.</div>
    <div v-else-if="loading">Loading...</div>
    <form v-else>
        <div v-if="success">
            Your submission has successfully updated.
            <button @click="success = false">Close</button>
        </div>
        <fieldset>
            <legend>Graduation Regalia Requirements</legend>
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
                    <label for="gownRequired"> Is Gown Hire Required? </label>
                    <select
                        name="gownRequired"
                        id="gownRequired"
                        v-model="submission.gownRequired"
                    >
                        <option :value="false">
                            No, I do not require a gown to be hired
                        </option>
                        <option :value="true">
                            Yes, I do require a gown to be hired
                        </option>
                    </select>
                    <fieldset v-if="submission.gownRequired">
                        <legend>
                            Gowns can be collected from B Block during Gown Room
                            open hours.
                        </legend>
                        <div>
                            <label for="gownLength"> Gown Length </label>
                            <select
                                name="gownLength"
                                id="gownLength"
                                v-model="submission.gownLength"
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
                                v-model="submission.preferredGown"
                            >
                                <option value="">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="regaliaInfo"
                                >What does your regalia look like? Gown colour,
                                hood details, etc.</label
                            >
                            <textarea
                                id="regaliaInfo"
                                name="regaliaInfo"
                                v-model="submission.regaliaInfo"
                            />
                        </div>
                    </fieldset>
                </div>
                <div>
                    <label for="trencherRequired">
                        Is Trencher Hire Required?
                    </label>
                    <select
                        name="trencherRequired"
                        id="trencherRequired"
                        v-model="submission.trencherRequired"
                    >
                        <option :value="false">
                            No, I do not require a matching Trencher
                        </option>
                        <option :value="true">
                            Yes, I require a matching Trencher
                        </option>
                    </select>
                    <fieldset v-if="submission.trencherRequired">
                        <legend>Trencher Details</legend>
                        <label for="trencherSize"> Trencher Size </label>
                        <select
                            name="trencherSize"
                            id="trencherSize"
                            v-model="submission.trencherSize"
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
                        v-model="submission.comments"
                    />
                </div>
            </fieldset>
            <fieldset>
                <legend>Submit</legend>
                <button @click="handleSubmit">Submit form</button>
            </fieldset>
        </fieldset>
    </form>
</template>

<script>
import gql from "graphql-tag";

const fields = `id
                regaliaInfo
                trencherSize
                deliveryLocation
                comments
                pickup
                gownRequired
                trencherRequired
                gownLength
                preferredGown
                gownRequirementsOther
                gownInstitutionOther
                ceremonyIdsJSON
                ceremonies {
                    nodes {
                        id
                    }
                }
            `;

export default {
    name: "App",
    props: {
        memberId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            success: false,
            error: false,
            loading: true,
            submission: {},
            ceremonies: [],
            trencherMinSize: 52,
            trencherMaxSize: 65,
        };
    },
    computed: {
        trencherRange() {
            return this.trencherMaxSize - this.trencherMinSize + 1;
        },
    },
    watch: {
        submission() {
            delete this.submission.__typename;
            this.loading = false;
            this.ceremonies.forEach((ceremony) => {
                ceremony.attending = this.submission.ceremonies?.nodes.reduce(
                    (prev, { id }) => {
                        if (id === ceremony.id) {
                            return true;
                        }
                        return prev;
                    },
                    false
                );
            });
            delete this.submission.ceremonies;
        },
        ceremonies: {
            handler() {
                this?.syncCeremonyIds();
            },
            deep: true,
        },
    },
    methods: {
        syncCeremonyIds() {
            this.submission.ceremonyIdsJSON = JSON.stringify(
                this.ceremonies.reduce((prev, next) => {
                    if (next.attending) {
                        prev.push(parseInt(next.id));
                    }
                    return prev;
                }, [])
            );
        },
        handleErrors(errors) {
            console.log(errors);
            this.error = true;
        },
        handleSubmit(e) {
            e.preventDefault();
            this.loading = true;
            if (this.submission.id) {
                this.$apollo
                    .mutate({
                        mutation: gql`
                            mutation updateSubmission($input: UpdateSubmissionInput!) {
                                updateSubmission(input: $input) {
                                    ${fields}
                                }
                            }
                        `,
                        variables: {
                            input: this.submission,
                        },
                        update: (store, result) => {
                            this.submission = {
                                ...result.data.updateSubmission,
                            };
                            this.success = true;
                        },
                    })
                    .catch((errors) => this.handleErrors(errors));
            } else {
                this.$apollo
                    .mutate({
                        mutation: gql`
                    mutation createSubmission($input: CreateSubmissionInput!) {
                        createSubmission(input: $input) {
                            ${fields}
                        }
                    }
                `,
                        variables: {
                            input: this.submission,
                        },
                        update: (store, result) => {
                            this.submission = {
                                ...result.data.createSubmission,
                            };
                            this.success = true;
                        },
                    })
                    .catch((errors) => this.handleErrors(errors));
            }
        },
    },
    apollo: {
        submission: {
            query() {
                return gql`
                query readSubmissions($memberId: Int!) {
                    readSubmissions(filter: {memberId: {eq: $memberId}}) {
                        nodes {
                            ${fields}
                        }
                    }
                }
            `;
            },
            variables() {
                return {
                    memberId: this.memberId,
                };
            },
            update: (data) =>
                data.readSubmissions?.nodes.length
                    ? data.readSubmissions.nodes.map((item) => {
                          return { ...item };
                      })[0]
                    : {
                          id: 0,
                          regaliaInfo: "",
                          trencherSize: 52,
                          deliveryLocation: null,
                          comments: null,
                          pickup: null,
                          gownRequired: false,
                          trencherRequired: false,
                          gownLength: "M",
                          preferredGown: null,
                          gownRequirementsOther: null,
                          gownInstitutionOther: null,
                      },
            error(errors) {
                this.handleErrors(errors);
            },
        },
        ceremonies: {
            query() {
                return gql`
                    query {
                        readCeremonies {
                            nodes {
                                id
                                title
                            }
                        }
                    }
                `;
            },
            update: (data) =>
                data.readCeremonies?.nodes.map((item) => {
                    return { ...item, attending: false };
                }),
            error(errors) {
                this.handleErrors(errors);
            },
        },
    },
};
</script>
