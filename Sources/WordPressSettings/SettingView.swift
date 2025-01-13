import SwiftUI

@MainActor @Observable
class SettingsSuiteViewModel {

    var suite: SettingsSuite?

    var isLoading: Bool = true

    var error: Error?

    func fetchData(from url: URL) async {
        do {
            self.isLoading = true
            let (data, _) = try await URLSession.shared.data(from: url)
            self.suite = try JSONDecoder().decode(SettingsSuite.self, from: data)
            self.isLoading = false
        } catch {
            self.error = error
        }
    }
}

struct SettingsSuiteView: View {

    @State
    var viewModel: SettingsSuiteViewModel

    var body: some View {
        VStack(alignment: .leading) {
            if self.viewModel.isLoading {
                ProgressView().controlSize(.large)
            } else if let suite = viewModel.suite {
                    ScrollView {
                        HStack {
                            ForEach(suite.pages) { page in
                                Text(page.title)
                            }
                    }
                }
                ForEach(suite.pages) { page in
                    SettingsPageView(page: page)
                }
            }

            if let text = viewModel.error?.localizedDescription {
                Text(text)
            }
        }
        .navigationTitle(viewModel.suite?.title ?? "")
        .padding()
        .task {
            let url = URL(string: "http://localhost/wp-json/remote-settings/jetpack")!
            await viewModel.fetchData(from: url)
        }
    }
}

struct SettingsPageView: View {
    let page: SettingPage

    var body: some View {
        VStack(alignment: .leading) {
            Text(page.title)
                .font(.title2)
                .lineLimit(2)

            Text(page.description)
                .font(.caption)
                .fixedSize(horizontal: false, vertical: true)
                .lineLimit(nil)

            ForEach(page.groups) { group in
                SettingGroupView(group: group)
            }
        }
    }
}

struct SettingGroupView: View {
    let group: SettingGroup

    var body: some View {
        VStack(alignment: .leading) {
            VStack(alignment: .leading) {
                Text(group.title).font(.title2)
                if let headerText = group.headerText {
                    Text(headerText).font(.subheadline)
                }

                ForEach(group.settings) { setting in
                    SettingView(setting: setting, binding: .constant(false))
                }

                if let footerText = group.footerText {
                    Text(footerText).font(.footnote)
                }
            }.padding(EdgeInsets(top: 4, leading: 4, bottom: 4, trailing: 4))

        }
        .background(Color.white)
        .border(Color.gray, width: 1.0)
        .padding(
            EdgeInsets(top: 0, leading: 0, bottom: 16, trailing: 0)
        )
    }
}

struct SettingView: View {
    let setting: Setting

    var binding: Binding<Bool>

    var body: some View {
        switch setting.value {
            case .bool(let bool):
                BooleanSettingView(binding: binding, title: setting.key, description: setting.description)
            case .string(let string):
                Text("string")
            case .color(let string):
                Text("color")
            case .date(let date):
                Text("date")
            case .set:
                Text("set")
            case .uuid(let uUID):
                Text("uuid")
            case .postId(let int):
                Text("postid")
            case .mediaId(let int):
                Text("mediaid")
            case .userId(let int):
                Text("user")
            case .role(let string):
                Text("role")
        }
    }
}

struct BooleanSettingView: View {

    let binding: Binding<Bool>
    let title: String
    let description: String?

    var body: some View {
        VStack(alignment: .leading) {
            Toggle(isOn: self.binding) {
                Text(title).font(.body)
            }

            if let description {
                Text(description).font(.footnote)
            }
        }
    }
}

#Preview {
    NavigationView {
        ScrollView {
            SettingsSuiteView(viewModel: SettingsSuiteViewModel())
                .background(Color.gray)
        }
    }
}
