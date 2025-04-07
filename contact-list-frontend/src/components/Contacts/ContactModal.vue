<template>
    <div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContactModalLabel">
                        {{ isEdit ? 'Edit Contact' : 'Add Contact' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="handleSubmit">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" v-model="form.name">
                            <div v-if="contactStore.error?.name" class="invalid-feedback d-block">
                                {{ contactStore.error?.name[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" v-model="form.email">
                            <div v-if="contactStore.error?.email" class="invalid-feedback d-block">
                                {{ contactStore.error?.email[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" v-model="form.phone_number">
                            <div v-if="contactStore.error?.phone_number" class="invalid-feedback d-block">
                                {{ contactStore.error?.phone_number[0] }}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                                <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-1"></span>
                                {{ isEdit ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Modal } from 'bootstrap'
import { useContactStore } from '@/stores/contactStore'

const contactStore = useContactStore()
const emit = defineEmits(['contact-added'])

const form = reactive({
    id: null,
    name: '',
    email: '',
    phone_number: ''
})

const isSubmitting = ref(false)
const isEdit = ref(false)
const response = ref([])

let modal = null


const handleSubmit = async () => {
    isSubmitting.value = true
    try {
        if (isEdit.value) {
            response.value = await contactStore.updateContact(form.id, { ...form })
        } else {
            response.value = await contactStore.createContact({ ...form })
            emit('contact-added')
        }

        if (response.value.success) {
            modal.hide()
        }

    } finally {
        isSubmitting.value = false
    }
}

const show = (contact = null) => {
    contactStore.resetError()
    if (contact) {
        isEdit.value = true
        form.id = contact.id
        form.name = contact.name
        form.email = contact.email
        form.phone_number = contact.phone_number
    } else {
        isEdit.value = false
        form.id = null
        form.name = ''
        form.email = ''
        form.phone_number = ''
    }
    if (!modal) {
        modal = new Modal(document.getElementById('addContactModal'))
    }
    modal.show()
}

const hide = () => {
    if (modal) {
        modal.hide()
    }
}

defineExpose({ show, hide })
</script>