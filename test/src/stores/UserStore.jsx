export const UserContext = React.createContext({ user, setUser })

const UserStore = props => {
    const [user, setUser] = useState(null)
    
    return (
        <UserContext.Provider value={{ user, setUser }}>
        {props.children}
        </UserContext.Provider>
    )
}

export default AuthStore