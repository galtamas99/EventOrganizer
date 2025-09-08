import { api } from './api'

export const bookingService = {
  getBookings: getBookings(),
  getBookingById: getBookingById(),
  updateBooking: updateBooking(),
  deleteBooking: deleteBooking(),
  createBooking: createBooking(),
  getMyBookings: getMyBookings(),
}

function getMyBookings() {
  return (searchText, page, size) =>
    api.get(`api/my-bookings?page=${page || 0}&size=${size || 0}&sort=created_at&order=asc&search=${searchText || ''}`)
}

function createBooking() {
  return (bookingData) => api.post('api/bookings', bookingData)
}

function getBookingById() {
  return (id) => api.get(`api/bookings/${id}`)
}

function updateBooking() {
  return (id, eventData) => api.put(`api/bookings/${id}`, eventData)
}

function deleteBooking() {
  return (id) => api.delete(`api/bookings/${id}`)
}

function getBookings() {
  return (searchText, page, size) =>
    api.get(`api/bookings?page=${page || 0}&size=${size || 0}&sort=created_at&order=asc&search=${searchText || ''}`)
}
