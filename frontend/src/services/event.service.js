import { api } from './api'

export const eventService = {
  getEvents: getEvents(),
  getEventById: getEventById(),
  createEvent: createEvent(),
  updateEvent: updateEvent(),
  deleteEvent: deleteEvent(),
}

function getEventById() {
  return (id) => api.get(`api/events/${id}`)
}
function createEvent() {
  return (eventData) => api.post('api/events', eventData)
}

function updateEvent() {
  return (id, eventData) => api.put(`api/events/${id}`, eventData)
}

function deleteEvent() {
  return (id) => api.delete(`api/events/${id}`)
}

function getEvents() {
  return (searchText, page) => api.get(`api/events?page=${page || 0}&size=10&sort=starts_at&order=asc&search=${searchText || ''}`)
}
