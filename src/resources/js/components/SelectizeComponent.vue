<template>
    <div>
        <selectize ref="select" v-model="localValue" :options="options" @change="mChange">
            <option value="">Seleccionar</option>
            <option v-for="(value, key) of collect" :key="key" :value="key">{{ value }}</option>
        </selectize>
    </div>
</template>

<script>
import Selectize from 'vue2-selectize'

export default {
    components: { Selectize },
    props: {
        value: {
            type: [String, Number],
            default: null,

        },
        options: {
            type: Object,
            default: () => { return {} },

        },
        collect: {
            type: Object,
            default: () => { return {} },

        }
    },
    data() {
        return {
            localValue: this.value
        }
    },
    watch: {
        localValue: function (value) {
            this.cValue = value
            this.mChange(this.$refs.select)
        }
    },
    computed: {
        cValue: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    },
    methods: {
        mChange (select) {
            this.$emit('change', select);
        }
    },
}
</script>

<style lang="sass" scoped>
    @import "~selectize/dist/css/selectize.bootstrap3.css"
    .is-invalid .selectize-control .selectize-input
        border-color: #e3342f
        padding-right: calc(1.6em + 0.75rem)
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23e3342f' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e3342f' stroke='none'/%3e%3c/svg%3e")
        background-repeat: no-repeat
        background-position: right calc(0.4em + 1.4rem) center
        background-size: calc(0.8em + 0.375rem) calc(0.8em + 0.375rem)

</style>