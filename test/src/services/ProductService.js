
import axios from 'axios'

export const getProducts = async () => {
    try {
        const response = await axios.get('/api/products')
        return response.data
    } catch (error) {
        console.log(error)
    }
}

export const getProductbyId = async (id) => {
    try {
        const response = await axios.get('/api/products/' + id)
        return response.data
    } catch (error) {
        console.log(error)
    }
}

export const editProduct = async (id, product) => {
    try {
        const response = await axios.put('/api/products/' + id, product)
        return response.data
    } catch (error) {
        console.log(error)
    }
}

export const deleteProduct = async (id) => {
    try {
        const response = await axios.delete('/api/products/' + id)
        return response.data
    } catch (error) {
        console.log(error)
    }
}
        