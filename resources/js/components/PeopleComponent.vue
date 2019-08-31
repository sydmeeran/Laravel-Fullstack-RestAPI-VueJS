<template>
<div>
    <form @submit.prevent="addPerson" class="card card-body mb-4">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="First Name" v-model="person.first_name"/>
        </div>

        <div class="form-group">
            <input type="text" class="form-control" placeholder="Last Name" v-model="person.last_name"/>
        </div>

        <div class="form-group">
            <input type="text" class="form-control" placeholder="E-Mail" v-model="person.email"/>
        </div>

        <div class="form-group">
            <input type="text" class="form-control" placeholder="Phone" v-model="person.phone"/>
        </div>

        <div class="form-group">
            <input type="text" class="form-control" placeholder="City" v-model="person.city"/>
        </div>
        <button type="submit" class="btn btn-info">Save</button>
    </form>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchPeople(pagination.prev_page_url)">Previous</a></li>
            <li class="page-item"><p class="page-link text-dark" href="#">Page {{pagination.current_page+" of "+pagination.last_page}}</p></li>
            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchPeople(pagination.next_page_url)">Next</a></li>
        </ul>
    </nav>
    <div class="card card-body mb-2 col-md-12" v-for=" person in people" v-bind:key="person.id">
        <div class="row">
            <div class="col-md-6">
                <h4><b>Name :</b> {{ person.first_name+" "+person.last_name }} </h4>
                <h4><b>E-Mail :</b> {{ person.email }}</h4>
            </div>
            <div class="col-md-6">
                <h4><b>Phone :</b> {{ person.phone }}</h4>
                <h4><b>City :</b> {{ person.city }}</h4>
            </div>
        </div>
        <hr>
        <button class="btn btn-warning mb-2" @click="editPerson(person)">Edit</button>
        <button class="btn btn-danger" @click="deletePerson(person.id)">Delete</button>


    </div>
</div>
</template>

<script>
    export default{
        data(){
        return{
            people : [],
            person : {
                id: '',
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                city: ''
            },
            person_id: '',
            pagination: {},
            edit: false
        };
    },
    created(){
        this.fetchPeople();
    },
    methods:
    {
        fetchPeople(page_url){
            let vm = this;
            page_url = page_url || 'api/v1/people/';
                fetch(page_url)
                    .then(res => res.json())
                    .then( res => {
                        this.people = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err=> console.log(err));
                },
                makePagination(meta , links){
                let pagination = {
                    current_page : meta.current_page,
                    last_page : meta.last_page,
                    next_page_url : links.next,
                    prev_page_url: links.prev
                }

                this.pagination = pagination;
            },
            deletePerson(id){
                if(confirm('Are you sure?'))
                {
                    fetch('api/v1/person/'+id,{
                        method:'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Person Removed');
                        this.fetchPeople();
                    })
                    .catch(err => console.log(err));
                }
            },
            addPerson()
            {
                if(this.edit === false)
                {
                    // Add Person
                    if(confirm('Are you sure?'))
                    {
                        if(this.person.first_name !== '' && this.person.last_name !== '' && this.person.email !== '' && this.person.phone !== '' && this.person.city !== '')
                        {
                            fetch('api/v1/person',{
                                method: 'post',
                                body: JSON.stringify(this.person),
                                headers: {
                                    "content-type" : "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.person.first_name = '';
                                this.person.last_name = '';
                                this.person.email = '';
                                this.person.phone = '';
                                this.person.city = '';
                                alert('Person Added');
                                this.fetchPeople();
                            })
                            .catch(err => console.log(err));
                        }
                        else
                        {
                            alert("Fill the form please");
                        }
                    }
                }
                else
                {
                    // Update Person
                    if(confirm('Are you sure?'))
                    {
                        if(this.person.first_name !== '' && this.person.last_name !== '' && this.person.email !== '' && this.person.phone !== '' && this.person.city !== '')
                        {
                            fetch('api/v1/person/'+this.person.person_id,{
                                method: 'put',
                                body: JSON.stringify(this.person),
                                headers: {
                                    "content-type" : "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.person.first_name = '';
                                this.person.last_name = '';
                                this.person.email = '';
                                this.person.phone = '';
                                this.person.city = '';
                                this.edit = false;
                                alert('Person Updated');
                                this.fetchPeople();
                            })
                            .catch(err => console.log(err));
                        }
                        else
                        {
                            alert("Fill the form please");
                        }
                    }
                }

            },
            editPerson(person)
            {
                this.edit = true;
                this.person.person_id = person.id;
                this.person.id = person.id;
                this.person.first_name = person.first_name;
                this.person.last_name = person.last_name;
                this.person.email = person.email;
                this.person.phone = person.phone;
                this.person.city = person.city;
            }
        }
    }
</script>