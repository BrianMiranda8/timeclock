<style>
    .modal-container {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 6;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        /* display: flex; */
        user-select: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 680px;
        min-height: 250px;
        gap: 15px;
        padding: 5px;
        background-color: white;
        border-radius: 4px;
    }
 .close{
	 font-size: 2.8rem;
	 margin-right: 5px;
	 }
.close:hover{
	font-size: 2.7rem;
	color: gray;
	cursor: pointer;
	}
</style>

<div class="modal-container" id="main-modal-container">
    <div class="modal-content" id="modal-content">
        <div style="width: 100%; text-align:center;margin-top:5px; font-size:1.5rem;font-weight:600;display:flex;align-items:center;justify-content:space-between;">
            <span></span>
            <span id="punch-title">
            </span>
            <span onclick="modal.closeModal()" class="close" title="Close/No Save">&times</span>
        </div>
        <div style="font-size: 1.1rem;margin: 15px auto 0 auto;display: flex;justify-content: center;align-items: center;gap: 10px;">


            <button class="button" disabled style="background-color: red; color:white;" id="delete-button" onclick="deletePunch();">Delete Punch</button>
        </div>
        <div style=" max-height:150px; overflow-y:auto; overflow-x:hidden;">

            <table class="manager-report-table" style="margin: 30px auto 0px auto !important;font-size:1rem; text-align:center">
                <tbody>

                    <tr>
                        <th>IN</th>
                        <th>OUT</th>
                        <th>DEP</th>
                    </tr>
                    <tr id="main-punch-row" data-punch-id="">
                        <td>
                            <input type="datetime-local" onchange="enablePunchSave();" date-type="start" name="editDateIn" id="editDateIn" style="text-align: center;">
                        </td>
                        <td>
                            <input type="datetime-local" date-type="end" onchange="enablePunchSave();" name="editDateOut" id="editDateOut" style="text-align: center;">
                        </td>
                        <td>
                            <select data-main-input style="font-size:.9em; border-right: 1px solid #F3F3F3 !important; text-align:center;" id="">

                            </select>
                        </td>
                    </tr>



                </tbody>
            </table>
        </div>
        <div style="font-size: 1.1rem;margin: 15px auto 0 auto;display: flex;justify-content: center;align-items: center;gap: 10px;">

            <button class="button" id="save-punch-change" style="width: 100px;" disabled onclick="save()">Save</button>

        </div>

    </div>
</div>

<script>
	var editDateIn = document.getElementById('editDateIn')
	var editDateOut = document.getElementById('editDateOut')
	function enablePunchSave(){
		if(editDateIn.value != "" && editDateOut.value != "")
		{
		document.getElementById('save-punch-change').removeAttribute('disabled')
		}
	}
    const punchesTable = {
        mainPunches: {
            employeeid: null,
            punchid: -1,
            in_time: null,
            out_time: null,
            department: null
        },
        mainRow: document.querySelector("tr#main-punch-row[data-punch-id]"),
        dateInputs: document.querySelectorAll('tr#main-punch-row td > input'),
        depSelect: document.querySelector('tr#main-punch-row td > select'),

        setInputs(ID, start, end, departments, department, employeeid = null) {
            this.updatePunch(ID, start, end, department, employeeid);

            this.mainRow.setAttribute('data-punch-id', ID);

            this.mainPunches.employeeid = employeeid;
            let depArray = departments.split(',');
            this.dateInputs[0].value = start;
            this.dateInputs[1].value = end;

            if (depArray.length == 1) this.depSelect.disabled = true;
            for (let index = 0; index < depArray.length; index++) {
                let dep = depArray[index];
                let option = document.createElement('option');
                option.value = dep;
                option.innerText = dep;
                if (dep == department || depArray.length == 1) option.selected = true;

                this.depSelect.appendChild(option);

            }
        },
        updatePunch(ID, start, end, dep, employeeid = null) {
            this.mainPunches.department = dep;
            this.mainPunches.punchid = ID;
            this.mainPunches.in_time = start;
            this.mainPunches.out_time = end;
            this.mainPunches.employeeid = employeeid;
        },

        getPunchData() {
            return {
                punchid: this.mainPunches.punchid,
                in_time: this.dateInputs[0].value,
                out_time: this.dateInputs[1].value,
                department: this.depSelect.value,
                employeeid: this.mainPunches.employeeid
            }
        }

    }

    const modal = {
        modalContainer: document.getElementById('main-modal-container'),
        modalContent: document.getElementById('modal-content'),
        deleteButton: document.getElementById('delete-button'),
        punchTitle: document.querySelector('#punch-title'),
        openModal() {
            this.modalContainer.style.display = 'flex'
            let body = document.querySelector('body').style.overflowY = 'hidden';

        },
        closeModal() {
			document.getElementById('delete-button').style.display = "block"
            this.modalContainer.style.display = "none"
            this.deleteButton.disabled = true;
            let body = document.querySelector('body').style.overflowY = 'auto';

        },
        setTitle(title) {
            this.punchTitle.innerText = title;
        }
    }


    function save() {
        let data = punchesTable.getPunchData();

        if (data.out_time == null) {
            let confirm = alert('Out time is empty')
            return;
        }

        if (data.punchid == -1) {
            addPunch(data);
            return;
        }
        editPunch(data);
    }

    function newPunch(button, ID, department) {
        let name = button.getAttribute('data-name')
        modal.openModal();
        modal.setTitle(`Add new punch for ${name}`);

        punchesTable.setInputs(-1, null, null, department, '', ID);
		document.getElementById('delete-button').style.display = "none"

    }
    async function addPunch(data) {

        let response = await sendChanges('POST', data);
        window.location.reload();
        console.log(response);
    }


    async function editPunch(data) {

        let response = await sendChanges("PUT", data);
        window.location.reload();
	
		document.getElementById('delete-button').style.display = "block"
        // console.log(response);
    }

    async function sendChanges(method, data) {
        const HEADERS = {
            method,
            body: JSON.stringify(data)
        }

        try {
            let request = await fetch('../process/update-employee-punch.php', HEADERS);
            let response = await request;
            return response;
        } catch (error) {
            throw new Error(error);
        }

    }

    async function deletePunch() {
        let confirm = window.confirm('This is a permanent action are you sure?')
        if (!confirm) return;
        let data = punchesTable.getPunchData();
        let response = await sendChanges("DELETE", data);
        window.location.reload();

        console.log(response);

    }


    function openPunchModal(button, punchID, currentDep, departments) {
        modal.openModal();
        modal.deleteButton.disabled = false;
        let name = button.getAttribute("data-name");
        modal.setTitle(`Edit ${name} Punches`);
        let start = button.getAttribute('data-start');
        let end = button.getAttribute('data-end');
        punchesTable.setInputs(punchID, start, end, departments, currentDep)
    }
</script>
