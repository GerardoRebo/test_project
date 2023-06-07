import Api from "./Api";

export default {
    getUsers(page){
        return Api().get('/users', {
            params: {
                page,
            }
        })
    },
    storeUser(userForm){
        return Api().post('/users', userForm)
    },
    editUser(id,userForm){
        return Api().put(`/users/${id}`, userForm)
    },
    deleteUser(id){
        return Api().delete(`/users/${id}`)
    }
}