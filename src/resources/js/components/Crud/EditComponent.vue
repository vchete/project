<template>
    <div>
        <form class="form-horizontal">
            <div class="row">
                <div v-for="(field, index) of fields" :key="index" v-bind:class="{ 'col-sm-12': field.allgrid, 'col-sm-6': !field.allgrid }">
                    <div class="form-group" v-if="field.type === 'string'">
                        <label >{{ field.name }}</label>
                        <input type="text" class="form-control" :class="{'is-invalid': errors[field.fieldAs]}" v-model="cDataEdit[field.fieldAs]">
                        <span class="invalid-feedback" role="alert" v-if="errors[field.fieldAs]">
                            <strong>{{ errors[field.fieldAs].join(', ') }}</strong>
                        </span>
                    </div>
                    <div class="form-group" v-if="field.type === 'numeric'">
                        <label >{{ field.name }}</label>
                        <input type="number" class="form-control text-right" :class="{'is-invalid': errors[field.fieldAs]}" v-model="cDataEdit[field.fieldAs]">
                        <span class="invalid-feedback" role="alert" v-if="errors[field.fieldAs]">
                            <strong>{{ errors[field.fieldAs].join(', ') }}</strong>
                        </span>
                    </div>
                    <div class="form-group" v-if="field.type === 'textarea'">
                        <label >{{ field.name }}</label>
                        <textarea class="form-control" :class="{'is-invalid': errors[field.fieldAs]}" rows="3" v-model="cDataEdit[field.fieldAs]"></textarea>
                        <span class="invalid-feedback" role="alert" v-if="errors[field.fieldAs]">
                            <strong>{{ errors[field.fieldAs].join(', ') }}</strong>
                        </span>
                    </div>
                    <div class="form-group" v-if="dateType.includes(field.type)">
                        <label >{{ field.name }}</label>
                        <date-time-picker-component :type="field.type" v-model="cDataEdit[field.fieldAs]"/>
                        <span class="invalid-feedback" role="alert" v-if="errors[field.fieldAs]">
                            <strong>{{ errors[field.fieldAs].join(', ') }}</strong>
                        </span>
                    </div>
                    <div class="form-check form-group" v-if="field.type === 'bool'">
                        <input class="form-check-input" type="checkbox" :id="field.fieldAs" v-model="cDataEdit[field.fieldAs]" :checked="cDataEdit[field.fieldAs]">
                        <label class="form-check-label" :for="field.fieldAs">{{ field.name }}</label>
                    </div>
                    <div class="form-group" v-if="field.type === 'combobox'">
                        <label >{{ field.name }}</label>
                        <selectize-component 
                            v-model="cDataEdit[field.fieldAs]" 
                            :class="{'is-invalid': errors[field.fieldAs]}" 
                            :collect="field.collect" />
                            
                        <span class="invalid-feedback" role="alert" v-if="errors[field.fieldAs]">
                            <strong>{{ errors[field.fieldAs].join(', ') }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        </form>
        <button class="btn btn-danger" @click="mChangeComponent">Cancelar</button>
        <button class="btn btn-primary" @click="mSave">Guardar</button>
    </div>
</template>

<script>
    import DateTimePickerComponent from 'components/DateTimePickerComponent'
    import SelectizeComponent from 'components/SelectizeComponent'

    export default {
        components: { DateTimePickerComponent, SelectizeComponent },
        props: {
            fields: {
                type: Array,
                default: () => {return []},

            },
            errors: {
                type: Object,
                default: {},

            },
            dataEdit: {
                type: Object,
                default: {},
            }
        },
        data () {
            return {
                dateType: ['date', 'datetime', 'time'],
            }
        },
        computed: {
            cDataEdit () {
                return this.dataEdit
            }
        },
        methods: {
            mChangeComponent () {
                this.$emit('mResetErrors')
                this.$emit('mResetSubTitle')
                this.$emit('mChangeComponent')
            },
            mSave () {
                this.$emit('mSave', this.cDataEdit);
            },
        }
    }
</script>

<style lang="sass" scoped>
    .form-control
        display: block
        width: 100% !important
        height: calc(1.6em + 0.75rem + 2px) !important
        padding: 0.375rem 0.75rem
        font-size: 0.9rem
        font-weight: 400
        line-height: 1.6
        color: #495057
        background-color: #fff
        background-clip: padding-box
        border: 1px solid #ced4da
        border-radius: 0.25rem !important
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out
    textarea.form-control
        height: auto !important
    .form-check
        padding-top: 35px
    .invalid-feedback
        display: block !important
</style>