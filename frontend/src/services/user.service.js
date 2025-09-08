import { api } from './api'

export const userService = {
  getUsers: getUsers(),
  getUserById: getUserById(),
  createUser: createUser(),
  updateUser: updateUser(),
  deleteUser: deleteUser(),
  blockUser: blockUser(),
}

function blockUser() {
  return (id) => api.post(`api/users/${id}/block`)
}

function getUserById() {
  return (id) => api.get(`api/users/${id}`)
}
function createUser() {
  return (eventData) => api.post('api/users', eventData)
}

function updateUser() {
  return (id, eventData) => api.put(`api/users/${id}`, eventData)
}

function deleteUser() {
  return (id) => api.delete(`api/users/${id}`)
}

function getUsers() {
  return (searchText, page) => api.get(`api/users?page=${page || 0}&size=10&sort=created_at&order=asc&search=${searchText || ''}`)
}
