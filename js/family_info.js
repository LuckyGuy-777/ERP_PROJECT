// 필요한 DOM 요소들을 가져오기
const familyInformationDiv = document.getElementById("family-information");
const addFamilyMemberButton = document.getElementById("add-family-member");
const deleteFamilyMemberButton = document.getElementById("delete-family-member");
// 초기 구성원 인덱스 설정
let familyMemberIndex = 0;
// "구성원 추가" 버튼 클릭 이벤트 리스너
addFamilyMemberButton.addEventListener("click", () => {
    familyMemberIndex++;
    // 새로운 구성원 컨테이너 생성 및 설정
    const familyMemberContainer = document.createElement("div");
    familyMemberContainer.classList.add("family-member-container");
    familyMemberContainer.id = `family-member-${familyMemberIndex}`;
    // 구성원 정보 및 상태 div 생성
    const familyMemberInfoDiv = document.createElement("div");
    familyMemberInfoDiv.classList.add("family-member-info");

    const familyMemberStatusDiv = document.createElement("div");
    familyMemberStatusDiv.classList.add("family-member-status");
    // 각 입력 요소 생성 및 구성원 정보 div에 추가
    // create and append child elements for familyMemberInfoDiv
    const nameInput = document.createElement("input");
    nameInput.type = "text";
    nameInput.name = `family_member_name[${familyMemberIndex}]`;
    nameInput.placeholder = "구성원 이름을 입력하세요.";
    familyMemberInfoDiv.appendChild(nameInput);
    familyMemberInfoDiv.appendChild(document.createElement("br"));

    const relationshipInput = document.createElement("input");
    relationshipInput.type = "text";
    relationshipInput.name = `family_member_relationship[${familyMemberIndex}]`;
    relationshipInput.placeholder = "구성원 관계를 입력하세요.";
    familyMemberInfoDiv.appendChild(relationshipInput);
    familyMemberInfoDiv.appendChild(document.createElement("br"));

    const birthInput = document.createElement("input");
    birthInput.type = "date";
    birthInput.name = `family_member_birth[${familyMemberIndex}]`;
    familyMemberInfoDiv.appendChild(birthInput);
    familyMemberInfoDiv.appendChild(document.createElement("br"));

    // create and append child elements for familyMemberStatusDiv

    // 주석: Job nationality 입력 요소 생성
    const nationalityContainer = document.createElement("div");
    nationalityContainer.classList.add("radio-container");
    // 주석: 내국인 라디오 버튼 생성
    const domesticRadio = document.createElement("input");
    domesticRadio.type = "radio";
    domesticRadio.id = `family_domestic-${familyMemberIndex}`;
    domesticRadio.name = `family_nationality[${familyMemberIndex}]`;
    domesticRadio.value = "domestic";
    domesticRadio.required = true;
    nationalityContainer.appendChild(domesticRadio);
    // 주석: 내국인 라벨 생성
    const domesticLabel = document.createElement("label");
    domesticLabel.htmlFor = `family_domestic-${familyMemberIndex}`;
    domesticLabel.textContent = "내국인";
    nationalityContainer.appendChild(domesticLabel);
    // 주석: 외국인 라디오 버튼 생성
    const foreignRadio = document.createElement("input");
    foreignRadio.type = "radio";
    foreignRadio.id = `family_foreign-${familyMemberIndex}`;
    foreignRadio.name = `family_nationality[${familyMemberIndex}]`;
    foreignRadio.value = "foreign";
    foreignRadio.required = true;
    nationalityContainer.appendChild(foreignRadio);
    // 주석: 외국인 라벨 생성
    const foreignLabel = document.createElement("label");
    foreignLabel.htmlFor = `family_foreign-${familyMemberIndex}`;
    foreignLabel.textContent = "외국인";
    nationalityContainer.appendChild(foreignLabel);
    // 주석: Family Member 상태 div에 nationalityContainer 추가
    familyMemberStatusDiv.appendChild(nationalityContainer);
    familyMemberStatusDiv.appendChild(document.createElement("br"));

    // 주석: Employment status 입력 요소 생성
    const employmentContainer = document.createElement("div");
    employmentContainer.classList.add("radio-container");
    // 주석: 직장 있음 라디오 버튼 생성
    const employedRadio = document.createElement("input");
    employedRadio.type = "radio";
    employedRadio.id = `employed-${familyMemberIndex}`;
    employedRadio.name = `employment_status[${familyMemberIndex}]`;
    employedRadio.value = "employed";
    employedRadio.required = true;
    employmentContainer.appendChild(employedRadio);
    // 주석: 직장 있음 라벨 생성
    const employedLabel = document.createElement("label");
    employedLabel.htmlFor = `employed-${familyMemberIndex}`;
    employedLabel.textContent = "직장 있음";
    employmentContainer.appendChild(employedLabel);
    // 주석: 직장 없음 라디오 버튼 생성
    const unemployedRadio = document.createElement("input");
    unemployedRadio.type = "radio";
    unemployedRadio.id = `unemployed-${familyMemberIndex}`;
    unemployedRadio.name = `employment_status[${familyMemberIndex}]`;
    unemployedRadio.value = "unemployed";
    unemployedRadio.required = true;
    employmentContainer.appendChild(unemployedRadio);
    // 주석: 직장 없음 라벨 생성
    const unemployedLabel = document.createElement("label");
    unemployedLabel.htmlFor = `unemployed-${familyMemberIndex}`;
    unemployedLabel.textContent = "직장 없음";
    employmentContainer.appendChild(unemployedLabel);
    // 주석: Family Member 상태 div에 employmentContainer 추가
    familyMemberStatusDiv.appendChild(employmentContainer);
    // 구성원 컨테이너에 정보 div와 상태 div 추가
    familyMemberContainer.appendChild(familyMemberInfoDiv);
    familyMemberContainer.appendChild(familyMemberStatusDiv);
    // 구성원 목록에 새로운 구성원 컨테이너 추가
    familyInformationDiv.insertBefore(familyMemberContainer, addFamilyMemberButton);
});
// "구성원 삭제" 버튼 클릭 이벤트 리스너
deleteFamilyMemberButton.addEventListener("click", () => {
    // 구성원이 1명 이상인 경우에만 삭제 수행
    if (familyMemberIndex > 0) {
        const familyMemberContainer = document.getElementById(`family-member-${familyMemberIndex}`);
        familyInformationDiv.removeChild(familyMemberContainer);
        familyMemberIndex--;
    }
});
