import { api } from './api'

export const bookingService = {
  getBookings: getBookings(),
  getBookingById: getBookingById(),
  updateBooking: updateBooking(),
  deleteBooking: deleteBooking(),
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
  return (searchText, page) => api.get(`api/bookings?page=${page || 0}&size=10&sort=created_at&order=asc&search=${searchText || ''}`)
}
