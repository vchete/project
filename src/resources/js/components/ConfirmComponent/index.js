import ConfirmComponent from './ConfirmComponent.vue'
import { events } from './events'

export default {
	install(Vue, args = {}) {
		if (this.installed) return

		this.installed = true
		this.params    = args

		Vue.component(args.componentName || 'ConfirmComponent', ConfirmComponent)

		const confirm = params => {
			if (typeof params === 'object') {
				if (params.hasOwnProperty('callback') && typeof params.callback != 'function') {
				  let callbackType = typeof params.callback
				  throw new Error(
					`Callback es requerido`
				  )
				}
				events.$emit('mOpen', params)
			}
		}
		
		confirm.close = () => {
			events.$emit('mClose')
		}

		Vue.prototype.$confirm = confirm
		Vue['$confirm'] = confirm
	}
}