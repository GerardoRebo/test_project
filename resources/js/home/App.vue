<template>
	<v-app>
		<!-- <v-navigation-drawer app> </v-navigation-drawer> -->
		<v-app-bar app>
			<v-app-bar-nav-icon></v-app-bar-nav-icon>

			<v-toolbar-title>Application</v-toolbar-title>
		</v-app-bar>
		<v-main>
			<v-container>

				<!-- <v-row justify="space-around">
					<v-col cols="auto"> -->
				<div class="d-flex pa-4">
					<v-dialog transition="dialog-bottom-transition" max-width="600" v-model="dialog" justify="space-end" >
						<template v-slot:activator="{ on, attrs }">
							<v-btn color="primary" v-bind="attrs" v-on="on" @click="openAdd" class="ml-auto">Add User</v-btn>
						</template>
						<template v-slot:default="dialog">
							<v-card>
								<v-toolbar color="primary" dark>{{ message }}</v-toolbar>
								<v-card-text>
									<div class="text-h4 pa-12"></div>
									<v-text-field label="First Name" v-model="userForm.first_name"></v-text-field>
									<v-text-field label="Last Name" v-model="userForm.last_name"></v-text-field>
									<v-text-field label="Email" v-model="userForm.email"></v-text-field>
								</v-card-text>
								<v-card-actions class="justify-end">
									<v-btn text @click="submitForm">Submit</v-btn>
									<v-btn text @click="closeDialog">Close</v-btn>
								</v-card-actions>
							</v-card>
						</template>
					</v-dialog>
				</div>

				<!-- </v-col>
				</v-row> -->
				<div class="text-center justify-space-between d-flex pa-4">
					<v-chip class="ma-2" label>
						<strong>Total: </strong>{{ users.total }}
					</v-chip>
					<v-pagination v-model="users.current_page" :length="users.last_page" :total-visible="10"
						@input="getInput" @next="goNext" @previous="goPrevious"></v-pagination>
				</div>
				<v-simple-table dense>
					<template v-slot:default>
						<thead>
							<tr>
								<th class="text-left">
									First Name
								</th>
								<th class="text-left">
									Last Name
								</th>
								<th class="text-left">
									Email
								</th>
								<th class="text-left">
									Created at
								</th>
								<th class="text-left">
									Options
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="user in users.data" :key="user.id">
								<td>{{ user.first_name }}</td>
								<td>{{ user.last_name }}</td>
								<td>{{ user.email }}</td>
								<td>{{ user.created_at }}</td>
								<td style="display: flex;">
									<div style="margin-right: 2em;" @click="openEdit(user.id)">
										Edit
									</div>
									<div style="margin-right: 2em;" @click="deleteUser(user.id)">
										Delete
									</div>
								</td>
							</tr>
						</tbody>
					</template>
				</v-simple-table>
				<!-- If using vue-router -->
				<!-- <router-view></router-view> -->
			</v-container>
		</v-main>
	</v-app>
</template>

<script setup>
import Users from "../api/Users";
import { computed, onMounted, reactive, ref } from 'vue';
const users = ref([])
const dialog = ref(false)
const type = ref("add")
const userId = ref(null)
const userForm = reactive({
	first_name: "",
	last_name: "",
	email: "",
})
const message = computed(() => {
	if (type.value === "add") {
		return "Add user"
	}
	return "Edit user"
})
function openAdd() {
	type.value = "add"
}
function openEdit(id) {
	type.value = "edit"
	dialog.value = true;
	userId.value = id
	const element = users.value.data.find(user => user.id == id);

	userForm.first_name = element.first_name;
	userForm.last_name = element.last_name;
	userForm.email = element.email;

}
function editUser() {
	Users.editUser(userId.value, userForm)
		.then((response) => {
			if (response.status !== 200) {
				return alert("somthing went wrong");
			}
			let page = getQueryParam('page')
			if (page) {
				getUsers(page)
				return
			}
			getUsers()

		}).catch((error) => { }).finally(() => {
			closeDialog()
			cleanFields()
		})
}
function storeUser() {
	Users.storeUser(userForm).then((response) => {
		if (response.status !== 200) {
			return alert("somthing went wrong");
		}
		getUsers()
		cleanFields()
		closeDialog()

	}).catch((error) => {
		alert("something went wrong")
	}).finally(() => {
	})
}
function deleteUser(id) {
	Users.deleteUser(id).then((response) => {
		if (response.status !== 200) {
			return alert("somthing went wrong");
		}
		let page = getQueryParam('page')
		if (page) {
			getUsers(page)
			return
		}
		getUsers()

	}).catch((error) => { }).finally(() => {
	})
}
function submitForm() {
	if (type.value === "add") {
		return storeUser()
	}
	if (type.value === "edit") {
		return editUser()
	}
}
function cleanFields() {
	userForm.first_name = '';
	userForm.last_name = '';
	userForm.email = '';
}
function closeDialog() {
	dialog.value = false
	cleanFields()

}
function getUsers(page = 1) {
	Users.getUsers(page).then((response) => {
		if (response.status !== 200) {
			return alert("somthing went wrong");
		}
		users.value = response.data

	}).catch(() => { }).finally(() => { })
}
function goNext() {
	updateQueryString('page', input);
	getUsers(users.current_page + 1)
}
function goPrevious() {
	updateQueryString('page', input);
	getUsers(users.current_page - 1)
}
function getInput(input) {
	updateQueryString('page', input);
	getUsers(input)
}
function updateQueryString(key, value) {
	const url = new URL(window.location.href);
	const searchParams = new URLSearchParams(url.search);

	if (value === null) {
		searchParams.delete(key);
	} else {
		searchParams.set(key, value);
	}

	const newUrl = `${url.origin}${url.pathname}?${searchParams.toString()}`;
	window.history.replaceState({ path: newUrl }, '', newUrl);
}
function getQueryParam(key) {
	const url = new URL(window.location.href);
	const searchParams = new URLSearchParams(url.search);
	return searchParams.get(key);
}

onMounted(() => {

	getUsers(page)
})
</script>

<style>
.v-application {
	font-family: 'Poppins', sans-serif;
	line-height: 1;
}
</style>
