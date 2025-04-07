<template>
    <div class="container mt-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-white mb-4">
            <h5 class="mb-0 fw-bold text-primary">Contacts</h5>
            <button @click="showAddModal" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Add New Contact
            </button>
        </div>
        <!-- Loading Indicator -->
        <div v-if="contactStore.isLoading" class="text-center my-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading contacts...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="!contactStore.isLoading && !contactStore.contacts?.data?.data?.length" class="text-center py-5">
            <h4 class="text-muted">No contacts found</h4>
            <p class="text-muted">Add your first contact to get started</p>
        </div>
        <!-- Contacts Table -->
        <div v-else-if="contacts?.data?.data" class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="contact in contacts.data.data">
                                <td>{{ contact?.name }}</td>
                                <td>{{ contact?.email }}</td>
                                <td>{{ contact?.phone_number }}</td>
                                <td>
                                    <button @click="handleEditContact(contact)"
                                        class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button @click="handleDeleteContact(contact.id)"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="contacts.data.last_page > 1" class="card-footer">
                <nav aria-label="Contacts pagination">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item" :class="{ disabled: !contacts.data.prev_page_url }">
                            <button class="page-link" @click="changePage(contacts.data.current_page - 1)"
                                :disabled="!contacts.data.prev_page_url">
                                Previous
                            </button>
                        </li>

                        <li v-for="page in pages" :key="page" class="page-item"
                            :class="{ active: page === contacts.data.current_page }">
                            <button class="page-link" @click="changePage(page)">
                                {{ page }}
                            </button>
                        </li>

                        <li class="page-item" :class="{ disabled: !contacts.data.next_page_url }">
                            <button class="page-link" @click="changePage(contacts.data.current_page + 1)"
                                :disabled="!contacts.data.next_page_url">
                                Next
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <ContactModal ref="editContactModal" @contact-added="handleContactAdded" />
    </div>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue'
import { useContactStore } from '@/stores/contactStore'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import ContactModal from '@/components/Contacts/ContactModal.vue'

const router = useRouter()
const contactStore = useContactStore()
const contacts = ref([])
const editContactModal = ref(null)
const search = ref(null)
const perPage = ref(8)

const props = defineProps({
    currentPage: {
        type: Number,
        default: 1
    }
})

const showAddModal = () => {
    editContactModal.value.show()
}

const handleContactAdded = async () => {
    await fetchContacts(props.currentPage)
}

onMounted(() => {
    fetchContacts(props.currentPage)
})

const pages = computed(() => {
    if (!contacts.value.data.last_page) return []
    const range = []
    const total = contacts.value.data.last_page
    const current = contacts.value.data.current_page
    const delta = 2 // Number of pages to show around current page

    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
            range.push(i)
        } else if (i === current - delta - 1 || i === current + delta + 1) {
            range.push('...')
        }
    }

    return range.filter((item, index, array) => {
        return index === 0 || item !== array[index - 1]
    })
})

const fetchContacts = async (page = 1) => {
    await contactStore.fetchContacts(page, perPage.value, search.value) // Pass the page number to the store action
    contacts.value = contactStore.contacts

}

const changePage = async (page) => {
    if (page < 1 || page > contacts.value.last_page) return
    await fetchContacts(page, perPage.value, search.value)
}

const handleDeleteContact = async (id) => {
    const confirmResult = await Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    });

    if (confirmResult.isConfirmed) {
        await contactStore.deleteContact(id);

    }
}

const handleEditContact = (contact) => {
    editContactModal.value.show(contact)
}

</script>

<style scoped>
.table-responsive {
    overflow-x: auto;
}

.page-item.disabled .page-link {
    pointer-events: none;
    opacity: 0.6;
}

.page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>