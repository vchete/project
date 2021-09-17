<template>
    <div>
        <transition name="fade">
            <div class="modal fade show" tabindex="-1" role="dialog" v-if="show">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header" >
                        <h5 class="modal-title" v-if="params.title" v-html="params.title || null"></h5>
                        <button type="button" class="close" @click="mClose">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 v-html="params.message || null"></h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="mClose">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click.stop="e => mConfirm(e, true)">Aceptar</button>
                    </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { events } from './events'
    Vue.directive('focus', {
        inserted: function(el) {
            el.focus()
        }
    })
    const Component = {
        name: 'ConfirmComponent',
        data () {
            return {
                show: false,
                params: {}
            }
        },
        methods: {
            mOpen (params) {
                document.body.classList.add('modal-open');
				document.querySelector('#modal-backdrop-custom').style.display = 'block';
                this.params  = params
                this.show    = true;
            },
            mClose () {
                document.body.classList.remove('modal-open');
                document.querySelector('#modal-backdrop-custom').style.display = 'none';
                this.params  = {};
                this.show    = false;
            },
            mConfirm ({ target }, confirm) {
                if (this.params.callback) {
                    this.params.callback(confirm)
                    this.mClose();
                }
            }
        },
        mounted() {
            if (!document) return

            events.$on('mOpen', this.mOpen)
            events.$on('mClose', () => { this.mClose() })
        },
    }
    export default Component
</script>

<style lang='sass' scoped>
    .modal
        display: block !important
        z-index: 3000 !important
    .fade-enter-active, .fade-leave-active
        transition: opacity .3s !important
    .fade-enter, .fade-leave-to
        opacity: 0 !important
</style>