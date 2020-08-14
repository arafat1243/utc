export default class Auth{
    constructor(roles){
        this.roles = roles; 
    }
    title(){
        return this.roles.map(role => role.title)
    }
    permissions(){
        return this.roles.map(role => role.permissions)
    }
    can($permision){
        let ret = '';
        if (this.isAdmin()){
            ret = true;
        }else{
            if (typeof $permision === "object"){
                this.permissions().forEach((arr1) => $permision.forEach((arr2) => {
                    if (arr1.includes(arr2)) {
                        ret = true;
                    }
                }));
            }else{
                this.permissions().forEach(permission => {
                    if (permission.includes($permision)) {
                        ret = true;
                    }
                })
            }
        }
        return ret || false
    }
    isAdmin(){
        return this.title().includes('super_administrator');
    }
    isStudent() {
        return this.title().includes('student');
    }
    isEmploye() {
        return this.title().includes('employee');
    }
}