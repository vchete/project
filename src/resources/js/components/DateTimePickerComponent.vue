<template>
    <div>
        <div class="input-group datetimepicker date">
            <date-picker ref="input" :value="cDateFormatted" :config="cOption" class="form-control"></date-picker>
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="far fa-clock" v-if="type == 'time'"></i>
                    <i class="far fa-calendar-alt" v-else></i>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    import 'bootstrap/dist/css/bootstrap.css';
    import datePicker from 'vue-bootstrap-datetimepicker';
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
   
    export default {    
        props: {
            value: {
                type: [String, Date],
                default: null
            },
            options: {
                type: Object,
                default: () => {}
            },
            type: {
                type: String,
                default: 'date'
            },
            format: {
                type: String,
                default: null
            },
        },
        components: {
            datePicker
        },
        data() {
            return {
                dateTypeFormat: {date: 'DD/MM/YYYY', datetime: 'DD/MM/YYYY h:mm', time: 'h:mm'}
            }
        },
        computed: {
            cDateFormat () {
                return this.format || this.dateTypeFormat[this.type]
            },
            cOption () {
                return {
                    useCurrent: false,
                    format: this.cDateFormat,
                    locale: 'es',
                    ignoreReadonly: true,
                    ...this.options
                }
            },
            cDateFormatted: {
                get () {
                    if (this.value != null) {
                        return moment(this.value).format(this.cDateFormat)
                    }
                },
                set (value) {
                    const str = value ? moment(value).format('YYYY-MM-DD') : value
                    this.$emit('input', str)
                    $(this.$el).find('> input').trigger('input')
                }
            }
        },
        mounted() {
            $(this.$el).on('dp.change', e => {
                this.cDateFormatted = e.date || null
            })
        },
    }
</script>

<style lang="sass" scoped>
    .page-content
        padding-bottom: 300px !important
</style>
